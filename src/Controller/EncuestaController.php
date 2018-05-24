<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EncuestaController extends BaseController
{
    const NUM_ENCUESTAS_INDEX = 4;
    const NUM_ENCUESTAS_INDEX_HOME = 4;

    /**
     * @Route("/home/encuesta/{id}", name="encuesta")
     */
    public function indexAction($id)
    {
        $encuestaService = $this->get('encuesta_service');
        $encuesta = $encuestaService->getEncuestaById($id);

        $jsonContent = $this->serializar($encuesta);

        return $this->render('encuesta/encuesta.html.twig', array(
            'encuesta' => $jsonContent
        ));
    }

    /**
     * @Route ("/home/encuesta/comment/save", name="comentario")
     */
    public function saveCommentAction(Request $request){
        $texto = $request->get('texto');
        $encuesta = $request->get('encuesta');

        $encuestaService = $this->get('encuesta_service');
        $saved = $encuestaService->saveCommentInEncuesta($texto, $encuesta);

        if ($saved){
            return new Response();
        } else {
            $respuesta = "Ha habido un error al añadir su comentario. Lo sentimos.";
            return new Response($respuesta);
        }
    }

    /**
     * @Route ("/home", name="home")
     */
    public function homeAction(){
        $encuestaService = $this->get('encuesta_service');
        $offset = 0;

        $encuestas = $encuestaService->getEncuestasOrderby(array(), array('id' => 'DESC'), self::NUM_ENCUESTAS_INDEX_HOME, $offset);

        return $this->render('encuesta/home.html.twig', array('encuestas' => $encuestas));
    }

    /**
     * @Route ("/home/encuestas", name="encuestas")
     */
    public function showEncuestasAction(Request $request){
        $encuestaService = $this->get('encuesta_service');
        $offset = 0;

        $encuestas = $encuestaService->getEncuestasOrderby(array(), array('id' => 'ASC'), self::NUM_ENCUESTAS_INDEX, $offset);

        $num = $encuestaService->getTotalEncuestas();

        $size = $num[0]['1'];
        $min = 1;
        $max = 4;

        return $this->render('encuesta/mostrarEncuestas.html.twig', array('encuestas' => $encuestas, 'size' => $size, 'min' => $min, 'max' => $max));
    }

    /**
     * @Route ("/home/encuestas/next-prev", name="encuestasN")
     * @param Request $request
     * @return Response
     */
    public function paginationAction(Request $request){
        $min = $request->get('min');
        $max = $request->get('max');

        $encuestaService = $this->get('encuesta_service');

        $encuestas = $encuestaService->getEncuestasBetween($min, $max);

        $jsonContent = $this->serializar($encuestas);

        return new JsonResponse($jsonContent);
    }
}
