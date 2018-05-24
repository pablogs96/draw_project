<?php
/**
 * Created by PhpStorm.
 * User: pablo
 * Date: 24/05/18
 * Time: 15:28
 */

namespace App\Services;


use App\Entity\Usuario;
use App\Entity\Sorteo;
use App\Exceptions\AlreadySubscribedException;
use App\Exceptions\PasswordIncorrectException;
use App\Exceptions\UserNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;

class UsuarioService
{
    private $entityManager;
    private $logger;
    private $usuarioClass;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger, $usuarioClass)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->usuarioClass = $usuarioClass;
    }

    /**
     * @param $userData
     * @param Sorteo $actual
     * @param $num
     * @return array
     * @throws \Exception
     */
    public function borrarUser($userData, Sorteo $actual, $num) {
        $user = $this->getOneUsuarioBy(array('email' => $userData[0]));

        try{
            if ($user){
                $hash = $user->getPassword();
                if (password_verify($userData[1], $hash)){
                    $sorteos = $user->getSorteos();
                    if (!empty($sorteos)) {
                        foreach ($sorteos as $sorteo){
                            if ($sorteo->getId() == $actual->getId()){
                                $num += 1;
                            } else {
                                $num += 0;
                            }
                        }
                    } else {
                        throw new UserNotFoundException("El usuario no está registrado en ningún sorteo");
                    }
                    if ($num == 0 ) {
                        $titulo = "ERROR";
                        $respuesta = "Este usuario no está inscrito al sorteo actual";
                        $data = [$titulo, $respuesta];
                    } else if ($num > 0) {
                        $user->removeSorteo($actual);
                        $this->entityManager->persist($user);
                        $this->entityManager->flush();
                        $titulo = "¡Operación realizada con éxito!";
                        $respuesta = "Has sido borrado del sorteo actual";
                        $data = [$titulo, $respuesta];
                    }
                } else {
                    throw new PasswordIncorrectException("Contraseña incorrecta. Introduzca de nuevo su contraseña.");
                }
            } else {
                throw new UserNotFoundException("El usuario no está registrado en ningún sorteo");
            }
        }catch (PasswordIncorrectException $pie){
            $titulo = "ERROR";
            $respuesta = $pie->getMessage();
            $data = [$titulo, $respuesta];
        }catch (UserNotFoundException $unfe){
            $titulo = "ERROR";
            $respuesta = $unfe->getMessage();
            $data = [$titulo, $respuesta];
        }
        return $data;
    }

    public function getOneUsuarioBy($criteria)
    {
        return $this->entityManager->getRepository($this->usuarioClass)->findOneBy($criteria);
    }

    /**
     * @param $data
     * @param Sorteo $sorteoActual
     * @return bool
     * @throws AlreadySubscribedException
     * @throws PasswordIncorrectException
     */
    public function addUser($data, Sorteo $sorteoActual)
    {
        // usuario
        /** @var Usuario $usuario */
        $usuario = $this->getOneUsuarioBy(array('email' => $data[1]));

        if (!$usuario) {
            // NO EXISTE USUARIO EN BD ---> lo añado

            $pass = $data[2];
            $coded = password_hash($pass, PASSWORD_BCRYPT);
            $newUser = new Usuario();
            $newUser->setNombre($data[0]);
            $newUser->setEmail($data[1]);
            $newUser->setPassword($coded);
            $newUser->addSorteo($sorteoActual);

            try{
                $this->entityManager->persist($newUser);
                $this->entityManager->flush();
                return true;
            }catch (ORMException $e) {
                $this->logger->alert($e->getMessage());
            }

        } else if ($usuario) {
            // EXISTE USUARIO EN BD ---> compruebo contraseña correcta

            $hash = $usuario->getPassword();

            if (password_verify($data[2], $hash)) {
                // CONTRASEÑA CORRECTA ---> compruebo que no este en el sorteo actual
                $num = 0;
                $sorteos = $usuario->getSorteos()->getValues();
                /** @var Sorteo $sorteo */
                foreach ($sorteos as $sorteo) {
                    if ($sorteo->getId() === $sorteoActual->getId()) {
                        $num = $num + 1;
                    } else {
                        $num = $num + 0;
                    }
                }

                if ($num === 0) {
                    // NO TIENE EL SORTEO ASOCIADO

                    $usuario->setNombre($data[0]);
                    $usuario->addSorteo($sorteoActual);

                    try{
                        $this->entityManager->persist($usuario);
                        $this->entityManager->flush();
                        return true;
                    } catch (ORMException $e) {
                        $this->logger->alert($e->getMessage());
                    }

                } else {
                    // YA ESTA INSCRITO EN EL SORTEO
                    throw new AlreadySubscribedException('¡Ya estás inscrito en el sorteo!');
                }
            } else {
                // CONTRASEÑA INCORRECTA
                throw new PasswordIncorrectException('Ha habido un error al añadirte al sorteo, comprueba tu email y contraseña');
            }
        }
    }
}