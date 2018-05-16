<?php

namespace App\Controller;

use App\Entity\Encuesta;
use App\Entity\Premio;
use App\Entity\Sorteo;
use App\Entity\Usuario;
use App\Repository\EncuestaRepository;
use App\Repository\SorteoRepository;
use DateInterval;
use DateTime;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Doctrine\Common\Collections\ArrayCollection;
use App\Exceptions\AlreadySubscribedException;
use App\Exceptions\PasswordIncorrectException;
use App\Exceptions\UserNotFoundException;

class SorteoController extends Controller
{

    /**
     * @Route ("/home/sorteo", name="sorteo")
     */
    public function sorteoAction()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $sorteos = $entityManager->getRepository(Sorteo::class)->findAll();

        $sorteo = $entityManager->getRepository(Sorteo::class)->findBy(array(), array('fecha' => 'DESC'), 1, 0);
        /** @var Sorteo $sorteo_actual */
        $sorteo_actual = $sorteo[0];

        $id = $sorteo_actual->getId();

        /** @var Sorteo $aux */
        foreach ($sorteos as $aux) {
            if ($aux->getId() != $id) {
                $historial [] = $aux;
            }
        }

        $size = count($historial);
        $min = $size;
        $max = $size - 3;

        $reverse_historial = array_reverse($historial);

        $hist = [$reverse_historial[0], $reverse_historial[1], $reverse_historial[2], $reverse_historial[3]];

        return $this->render('encuesta/sorteo.html.twig', array('historial' => $hist, 'actual' => $sorteo_actual, 'size' => $size, 'min' => $min, 'max' => $max));
    }

    /**
     * @Route ("/home/sorteo/add", name="subscription")
     * @throws \Exception
     */
    public function subsciptionAction()
    {
        //get data from ajax
        $userData = [$_POST['name'], $_POST['mail'], $_POST['pass']];


        //recupero sorteo actual
        $entityManager = $this->getDoctrine()->getManager();
        $sorteo = $entityManager->getRepository(Sorteo::class)->findBy(array(), array('fecha' => 'DESC'), 1, 0);
        // cojo el objeto para no tener que trabajar con el array
        /** @var Sorteo $sorteo_actual */
        $sorteo_actual = $sorteo[0];

        // fecha de hoy
        /** @var datetime $date */
        $date = new DateTime();
        // fecha sorteo_actual
        $fecha_sorteo = $sorteo_actual->getFecha();


        //añadir, crear o ejecutar
        if ($date < $fecha_sorteo) {
            if ($sorteo_actual->getGanador()) {
                // SORTEO ACTIVO CON GANADOR ---> error, no debería de pasar nunca
                $respuesta = "¡Vaya! Parece que ha habido un error.";
                $titulo = "ERROR";
                $data = [$titulo, $respuesta];
                return new JsonResponse($data);
            } else {
                // SORTEO ACTIVO SIN GANADOR ---> añado usuario a sorteo

                try {
                    $this->addUser($userData, $sorteo_actual);
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
                return new JsonResponse($data);
            }
        } else if ($date >= $fecha_sorteo) {
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

    /**
     * @Route ("/home/sorteo/historial", name="historial")
     */
    public function historialAction(Request $request)
    {
        $min = $request->query->get('min');
        $max = $request->query->get('max');


        $entityManager = $this->getDoctrine()->getManager();
        /** @var SorteoRepository $sorteoRespository */
        $sorteoRespository = $entityManager->getRepository(Sorteo::class);

        $sorteos = $sorteoRespository->findAll();

        /** @var Sorteo $last */
        $last = $sorteos[count($sorteos) - 1];

        if ($min == $last->getId()) {
            $min = $min - 1;
        }

        $show_sorteos = $sorteoRespository->findBetween($max, $min);

        //parseamos $encuestas
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();

        $normalizer->setCircularReferenceHandler(function ($show_sorteos) {
            return $show_sorteos->getId();
        });

        $serializer = new Serializer(array($normalizer), array($encoder));
        $jsonContent = $serializer->serialize($show_sorteos, 'json');

        return new JsonResponse($jsonContent);
    }

    /**
     *@throws \Exception
     */
    private function addUser($data, Sorteo $sorteoActual)
    {
        $entityManager = $this->getDoctrine()->getManager();
        /** @var Usuario $usuario */
        $usuario = $entityManager->getRepository(Usuario::class)->findOneBy(array('email' => $data[1]));

        if (!$usuario) {
            // NO EXISTE USUARIO EN BD ---> lo añado


            $pass = $data[2];
            $coded = password_hash($pass, PASSWORD_BCRYPT);
            $newUser = new Usuario();
            $newUser->setNombre($data[0]);
            $newUser->setEmail($data[1]);
            $newUser->setPassword($coded);
            $newUser->addSorteo($sorteoActual);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newUser);
            $entityManager->flush();
            return true;
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

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($usuario);
                    $entityManager->flush();
                    return true;
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

    private function createSorteo($fecha_sorteo)
    {
        try {
            $newFecha = $fecha_sorteo->add(new DateInterval('P1M'));

            $entityManager = $this->getDoctrine()->getManager();

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
        } catch (\Exception $e) {
            dump($e->getMessage());
        }
    }

    private function runSorteo(Sorteo $sorteo_actual)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $usuarios_sorteo = $sorteo_actual->getUsuarios();
        if (count($usuarios_sorteo) > 0) {
            if (count($usuarios_sorteo) > 1){
                /** @var Usuario $ganador */
                $ganador = $usuarios_sorteo[rand(0, count($usuarios_sorteo) - 1)];

                $sorteo_actual->setGanador($ganador);
                $entityManager->persist($sorteo_actual);
            }else if (count($usuarios_sorteo) == 1) {
                /** @var Usuario $ganador */
                $ganador = $usuarios_sorteo[0];

                $sorteo_actual->setGanador($ganador);
                $entityManager->persist($sorteo_actual);
            }

        } else if (count($usuarios_sorteo) == 0){
            dump("no hay usuarios");
        }
        $entityManager->flush();
    }

    /**
     * @param $newSorteo
     * @param $userData
     * @return Response
     * @throws \Exception
     */
    private function beforeAdding($newSorteo, $userData): Response
    {
        try {
            if ($newSorteo) {
                $this->addUser($userData, $newSorteo);
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

        return new JsonResponse($data);
    }

    /**
     * @Route ("/home/sorteo/leave", name="borrar")
     */
    public function borrarUserAction() {
        //get data from ajax
        $userData = [$_POST['mail'], $_POST['pass']];

        $entityManager = $this->getDoctrine()->getManager();
        /** @var Usuario $user */
        $user = $entityManager->getRepository(Usuario::class)->findOneBy(array('email' => $userData[0]));
        $sort = $entityManager->getRepository(Sorteo::class)->findBy(array(), array('fecha' => 'DESC'), 1, 0);

        /** @var Sorteo $actual */
        $actual = $sort[0];
        $num = 0;

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
        return new JsonResponse($data);
    }

    /**
     * @route ("/home/sorteo/area-personal", name="profile")
     */
    public function comprobarSorteoAction(){
        return $this->render('encuesta/comprobarSorteo.html.twig');
    }

    /**
     * @route ("/home/sorteo/login", name="login")
     */
    public function loginAction(Request $request){
        $user = new Usuario();

        $form = $this->createFormBuilder($user)
            ->add('email', EmailType::class, array('label' => 'Email'))
            ->add('password', PasswordType::class, array('label' => 'Contraseña'))
            ->add('save', SubmitType::class, array('label' => 'Aceptar'))
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

             $entityManager = $this->getDoctrine()->getManager();
             /** @var Usuario $usuario */
             $usuario = $entityManager->getRepository(Usuario::class)->findOneBy(array('email' => $user->getEmail()));

             if($usuario) {
                 $sorteos = $usuario->getSorteos();
                 $ganados = $usuario->getSorteosGanados();
                 $hash = $usuario->getPassword();
                 $encuesta = $this->getEncuesta();
                 $actual = $entityManager->getRepository(Sorteo::class)->findBy(array(), array('fecha' => 'DESC'), 1, 0);
                 /** @var Sorteo $sort_actual */
                 $sort_actual = $actual[0];
                 if (password_verify($user->getPassword(), $hash)){
                     return $this->render('encuesta/comprobarSorteo.html.twig', array('usuario' => $usuario, 'sorteos' => $sorteos,
                         'ganados' => $ganados, 'encuesta' => $encuesta, 'id_actual' => $sort_actual->getId()));
                 } else {
                     return $this->render('encuesta/login.html.twig', array(
                         'form' => $form->createView(),
                         'errorc' => "Contraseña incorrecta",
                     ));
                 }
             } else {
                 return $this->render('encuesta/login.html.twig', array(
                     'form' => $form->createView(),
                     'erroru' => "Usuario incorrecto",
                 ));
             }
        }


        return $this->render('encuesta/login.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    private function getEncuesta() {
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();

        $entityManager = $this->getDoctrine()->getManager();
        $last = $entityManager->getRepository(Encuesta::class)->findBy(array(), array('id' => 'DESC'), 1, 0);

        /** @var Encuesta $encuesta */
        $encuesta= $last[0];

        $normalizer->setCircularReferenceHandler(function ($encuesta) {
            return $encuesta->getId();
        });

        $serializer = new Serializer(array($normalizer), array($encoder));
        $jsonContent = $serializer->serialize($encuesta, 'json');

        return $jsonContent;
    }
}
