<?php

namespace App\Controller;

use App\Entity\Encuesta;
use App\Entity\Premio;
use App\Entity\Sorteo;
use App\Entity\Usuario;
use App\Repository\EncuestaRepository;
use App\Repository\SorteoRepository;
use App\Services\BorrarseService;
use App\Services\SubscriptionService;
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

        $last4 = $entityManager->getRepository(Sorteo::class)->findBy(array(), array('fecha' => 'DESC'), 4, 1);

        $sorteo = $entityManager->getRepository(Sorteo::class)->findBy(array(), array('fecha' => 'DESC'), 1, 0);
        /** @var Sorteo $sorteo_actual */
        $sorteo_actual = $sorteo[0];

        $size = $last4[0]->getId();
        $min = $size;
        $max = $size - 3;

        return $this->render('encuesta/sorteo.html.twig', array('historial' => $last4, 'actual' => $sorteo_actual, 'size' => $size, 'min' => $min, 'max' => $max));
    }

    /**
     * @Route ("/home/sorteo/add", name="subscription")
     */
    public function subsciptionAction(Request $request)
    {
        //get data from ajax
        $name = $request->get('name');
        $mail = $request->get('mail');
        $pass = $request->get('pass');

        $userData = [$name, $mail, $pass];

        //recupero sorteo actual
        $entityManager = $this->getDoctrine()->getManager();
        $sorteo = $entityManager->getRepository(Sorteo::class)->findBy(array(), array('fecha' => 'DESC'), 1, 0);

        /** @var Sorteo $sorteo_actual */
        $sorteo_actual = $sorteo[0];

        // fecha de hoy
        /** @var datetime $date */
        $date = new DateTime();

        // fecha sorteo_actual
        $fecha_sorteo = $sorteo_actual->getFecha();

        $subscriptionService = $this->container->get('SubscriptionService');
        $result = $subscriptionService->sorteoManagerAction($userData, $date, $fecha_sorteo, $sorteo_actual);

        return new JsonResponse($result);
    }

    /**
     * @Route ("/home/sorteo/historial", name="historial")
     */
    public function historialAction(Request $request)
    {
        $min = $request->query->get('min');
        $max = $request->query->get('max');

        $entityManager = $this->getDoctrine()->getManager();

        $sorteo = $entityManager->getRepository(Sorteo::class)->findBy(array(), array('fecha' => 'DESC'), 1, 0);

        /** @var Sorteo $last */
        $last = $sorteo[0];

        if ($min == $last->getId()) {
            $min = $min - 1;
        }

        $show_sorteos = $entityManager->getRepository(Sorteo::class)->findBetween($max, $min);

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
     * @Route ("/home/sorteo/leave", name="borrar")
     */
    public function borrarUserAction(Request $request) {
        //get data from ajax
        $mail = $request->get('mail');
        $pass = $request->get('pass');
        $userData = [$mail, $pass];

        $entityManager = $this->getDoctrine()->getManager();
        $sort = $entityManager->getRepository(Sorteo::class)->findBy(array(), array('fecha' => 'DESC'), 1, 0);

        /** @var Sorteo $actual */
        $actual = $sort[0];
        $num = 0;

        $borrarseService = $this->container->get('BorrarseService');
        $result = $borrarseService->borrarUser($userData, $actual, $num);

        return new JsonResponse($result);
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
