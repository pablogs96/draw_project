<?php

namespace App\Controller;

use App\Entity\Encuesta;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EncuestaController extends BaseController
{
    const NUM_ENCUESTAS_INDEX = 4;
    const NUM_ENCUESTAS_INDEX_HOME = 4;
    private $offset = 0;

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

        /** @var Encuesta $ultima */
        $ultima = $encuestaService->getEncuestasOrderby(array(), array('id' => 'DESC'), 1, 0)[0];
        /** @var Encuesta $primera */
        $primera = $encuestaService->getEncuestasOrderby(array(), array('id' => 'ASC'), 1, 0)[0];

        $encuestas = $encuestaService->getEncuestasOrderby(array(), array('id' => 'DESC'),
            self::NUM_ENCUESTAS_INDEX, $this->offset);

        $num = $encuestaService->getTotalEncuestas()[0]['1'];

        return $this->render('encuesta/mostrarEncuestas.html.twig', array('historial' => $encuestas,
            'offset' => $this->offset, 'ultimo' => $ultima->getId(), 'primero' => $primera->getId(), 'total' => $num));
    }

    /**
     * @Route ("/home/encuestas/next-prev", name="encuestasN")
     * @param Request $request
     * @return Response
     */
    public function paginationAction(Request $request){
        $op = $request->query->get('operation');
        $offset = $request->query->get('offset');

        $encuestaService = $this->get('encuesta_service');

        if ($op == 'next'){
            $offset += self::NUM_ENCUESTAS_INDEX;
        } elseif ($op == 'prev')
            $offset -= self::NUM_ENCUESTAS_INDEX;

        $encuestas = $encuestaService->getEncuestasOrderby(array(), array('id' => 'DESC'), self::NUM_ENCUESTAS_INDEX, $offset);

        $jsonContent = $this->serializar($encuestas);

        $data = [$jsonContent, $offset];

        return new JsonResponse($data);
    }
}
