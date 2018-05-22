<?php
/**
 * Created by PhpStorm.
 * User: pablogarcia
 * Date: 22/05/18
 * Time: 13:53
 */

namespace App\Services;


use App\Entity\Sorteo;
use App\Entity\Usuario;
use App\Exceptions\PasswordIncorrectException;
use App\Exceptions\UserNotFoundException;
use Doctrine\ORM\EntityManager;

class BorrarseService
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param $userData
     * @param Usuario $user
     * @param Sorteo $actual
     * @param $num
     * @return array
     * @throws \Exception
     */
    public function borrarUser($userData, Sorteo $actual, $num) {
        $entityManager = $this->entityManager;
        /** @var Usuario $user */
        $user = $entityManager->getRepository(Usuario::class)->findOneBy(array('email' => $userData[0]));
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
                        $entityManager->persist($user);
                        $entityManager->flush();
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

}