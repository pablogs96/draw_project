<?php

namespace App\Controller;

use App\Entity\Encuesta;
use App\Entity\Sorteo;
use App\Entity\Usuario;
use DateTime;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SorteoController extends BaseController
{
    const NUM_SORTEOS_INDEX = 3;
    private $offset = 1;

    /**
     * @Route ("/home/sorteo", name="sorteo")
     */
    public function sorteoAction()
    {
        $sorteoService = $this->get('sorteo_service');

        $last4 = $sorteoService->getSorteosOrderby(array(), array('fecha' => 'DESC'), self::NUM_SORTEOS_INDEX, $this->offset);
        $actual = $sorteoService->getSorteosOrderby(array(), array('fecha' => 'DESC'), 1, 0)[0];
        $ultimo = $sorteoService->getSorteosOrderby(array(), array('fecha' => 'DESC'), 1, 1)[0];
        $primero = $sorteoService->getSorteosOrderby(array(), array('fecha' => 'ASC'), 1, 0)[0];
        $total = $sorteoService->contarSorteos();
        $size = $total[0]['1'] - 1;


        return $this->render('encuesta/sorteo.html.twig', array('historial' => $last4, 'actual' => $actual,
            'offset' => $this->offset, 'ultimo' => $ultimo->getId(), 'primero' => $primero->getId(), 'total' => $size));
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

        $sorteoService = $this->get('sorteo_service');

        $sorteo = $sorteoService->getSorteosOrderby(array(), array('fecha' => 'DESC'), 1, 0);

        /** @var Sorteo $sorteo_actual */
        $sorteo_actual = $sorteo[0];

        // fecha de hoy
        /** @var datetime $date */
        $date = new DateTime();

        // fecha sorteo_actual
        $fecha_sorteo = $sorteo_actual->getFecha();

        $sorteoService = $this->container->get('sorteo_service');
        $result = $sorteoService->sorteoManagerAction($userData, $date, $fecha_sorteo, $sorteo_actual);

        return new JsonResponse($result);
    }

    /**
     * @Route ("/home/sorteo/historial", name="historial")
     */
    public function historialAction(Request $request)
    {
        $op = $request->query->get('operation');
        $offset = $request->query->get('offset');

        $sorteoService = $this->get('sorteo_service');

        if ($op == 'next'){
            $offset += self::NUM_SORTEOS_INDEX;
        } elseif ($op == 'prev')
            $offset -= self::NUM_SORTEOS_INDEX;

        $show_sorteos = $sorteoService->getSorteosOrderby(array(), array('fecha' => 'DESC'), self::NUM_SORTEOS_INDEX, $offset);

        $jsonContent = $this->serializar($show_sorteos);

        $data = [$jsonContent, $offset];

        return new JsonResponse($data);
    }

    /**
     * @Route ("/home/sorteo/leave", name="borrar")
     */
    public function borrarUserAction(Request $request) {
        //get data from ajax
        $mail = $request->get('mail');
        $pass = $request->get('pass');
        $userData = [$mail, $pass];

        $sorteoService = $this->get('sorteo_service');

        $sort = $sorteoService->getSorteosOrderby(array(), array('fecha' => 'DESC'), 1, 0);

        /** @var Sorteo $actual */
        $actual = $sort[0];
        $num = 0;

        $usuarioService = $this->container->get('usuario_service');

        $result = $usuarioService->borrarUser($userData, $actual, $num);

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
            $usuarioService = $this->get('usuario_service');

            /** @var Usuario $usuario */
             $usuario = $usuarioService->getOneUsuarioBy(array('email' => $user->getEmail()));

             if($usuario) {
                 $sorteos = $usuario->getSorteos();
                 $ganados = $usuario->getSorteosGanados();
                 $hash = $usuario->getPassword();
                 $encuesta = $this->getEncuesta();

                 $sorteoService = $this->get('sorteo_service');
                 $actual = $sorteoService->getSorteosOrderby(array(), array('fecha' => 'DESC'), 1, 0);

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
        $encuestaService = $this->get('encuesta_service');

        $last = $encuestaService->getEncuestasOrderby(array(), array('id' => 'ASC'), 1, 0);

        /** @var Encuesta $encuesta */
        $encuesta= $last[0];

        $jsonContent = $this->serializar($encuesta);

        return $jsonContent;
    }
}
