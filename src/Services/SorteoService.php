<?php
/**
 * Created by PhpStorm.
 * User: pablogarcia
 * Date: 22/05/18
 * Time: 10:47
 */

namespace App\Services;


use App\Entity\Premio;
use App\Entity\Sorteo;
use App\Entity\Usuario;
use App\Exceptions\AlreadySubscribedException;
use App\Exceptions\GanadorNotSettedException;
use App\Exceptions\PasswordIncorrectException;
use DateInterval;
use Doctrine\ORM\EntityManager;
use Exception;
use Psr\Log\LoggerInterface;

class SorteoService
{
    private $entityManager;
    private $logger;
    private $usuarioService;
    private $sorteoClass;

    public function __construct(EntityManager $entityManager, LoggerInterface $logger,
                                UsuarioService $usuarioService, $sorteoClass)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->usuarioService = $usuarioService;
        $this->sorteoClass = $sorteoClass;
    }

    /**
     * @param $userData
     * @param $date
     * @param $fecha_sorteo
     * @param Sorteo $sorteo_actual
     * @return array
     * @throws Exception
     */
    public function sorteoManagerAction($userData, $date, $fecha_sorteo,Sorteo $sorteo_actual){
        //añadir, crear o ejecutar
        if ($date < $fecha_sorteo) {
            if ($sorteo_actual->getGanador()) {
                // SORTEO ACTIVO CON GANADOR ---> error, no debería de pasar nunca
                $respuesta = "¡Vaya! Parece que ha habido un error.";
                $titulo = "ERROR";
                $data = [$titulo, $respuesta];
                return $data;
            } else {
                // SORTEO ACTIVO SIN GANADOR ---> añado usuario a sorteo

                try {
                    $this->usuarioService->addUser($userData, $sorteo_actual);
                    $respuesta = "Te has inscrito al sorteo. ¡Mucha suerte!";
                    $titulo ="ENHORABUENA";
                    $data = [$titulo, $respuesta];
                }catch (PasswordIncorrectException $pie ) {
                    $titulo ="ERROR";
                    $respuesta = $pie->getMessage();
                    $data = [$titulo, $respuesta];
                }catch (AlreadySubscribedException $ase) {
                    $titulo ="ERROR";
                    $respuesta = $ase->getMessage();
                    $data = [$titulo, $respuesta];
                }
                return $data;
            }
        } else{
            if ($sorteo_actual->getGanador()) {
                // SORTEO NO ACTIVO (FECHA MENOR) Y CON GANADOR ---> creo sorteo/añado usuario

                /** @var Sorteo $newSorteo */
                $newSorteo = $this->createSorteo($fecha_sorteo);

                return $this->beforeAdding($newSorteo, $userData);
            } else {
                // SORTEO NO ACTIVO (FECHA MENOR) Y SIN GANADOR ---> ejecuto sorteo/creo sorteo/añado usuario

                $this->runSorteo($sorteo_actual);

                /** @var Sorteo $newSorteo */
                $newSorteo = $this->createSorteo($fecha_sorteo);

                return $this->beforeAdding($newSorteo, $userData);
            }
        }
    }



    private function createSorteo($fecha_sorteo)
    {
        $entityManager = $this->entityManager;
        try {
            $newFecha = $fecha_sorteo->add(new DateInterval('P1M'));


            $premios = $entityManager->getRepository(Premio::class)->findAll();
            /** @var Premio $randomPremio */
            $randomPremio = $premios[rand(0, count($premios) - 1)];

            $newSorteo = new Sorteo();
            $newSorteo->setPremio($randomPremio->getTitle());
            $newSorteo->setImg($randomPremio->getImagen());
            $newSorteo->setFecha($newFecha);

            $entityManager->persist($newSorteo);
            $entityManager->flush();

            return $newSorteo;
        } catch (Exception $e) {
            $this->logger->alert($e->getMessage());
        }
    }

    private function runSorteo(Sorteo $sorteo_actual)
    {

        $usuarios_sorteo = $sorteo_actual->getUsuarios();
        try{
            if (count($usuarios_sorteo) > 0) {
                if (count($usuarios_sorteo) > 1){
                    /** @var Usuario $ganador */
                    $ganador = $usuarios_sorteo[rand(0, count($usuarios_sorteo) - 1)];

                    $sorteo_actual->setGanador($ganador);
                    $this->entityManager->persist($sorteo_actual);
                }
            } else if (count($usuarios_sorteo) == 0){
                $this->logger->info('No hay usuarios.');
            }
            $this->entityManager->flush();
        }catch (GanadorNotSettedException $gnse) {
            $this->logger->alert($gnse->getMessage());
        }catch (Exception $e) {
            $this->logger->info($e->getMessage());
        }

    }

    /**
     * @param $newSorteo
     * @param $userData
     * @return array
     * @throws Exception
     */
    private function beforeAdding($newSorteo, $userData)
    {
        try {
            if ($newSorteo) {
                $this->usuarioService->addUser($userData, $newSorteo);
            }
            $respuesta = "Atención: El sorteo anterior ha caducado. Te has inscrito a un nuevo sorteo. ¡Mucha suerte!";
            $titulo ="ENHORABUENA";
            $data = [$titulo, $respuesta];
        }catch (PasswordIncorrectException $pie ) {
            $titulo ="ERROR";
            $respuesta = $pie->getMessage();
            $data = [$titulo, $respuesta];
        }catch (AlreadySubscribedException $ase) {
            $titulo ="ERROR";
            $respuesta = $ase->getMessage();
            $data = [$titulo, $respuesta];
        }

        return $data;
    }


    public function getEncuestasOrderby($criteria, $order, $limit, $offset)
    {
    return $this->entityManager->getRepository($this->sorteoClass)->findBy($criteria, $order, $limit, $offset);
    }

    public function getSorteosBetween($min, $max)
    {
        return $this->entityManager->getRepository($this->sorteoClass)->findBetween($min, $max);
    }
}