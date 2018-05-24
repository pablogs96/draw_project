<?php

namespace App\Controller;

use App\Entity\Comentario;
use App\Entity\Encuesta;
use App\Repository\EncuestaRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Doctrine\Common\Persistence\ObjectManager;

class EncuestaController extends Controller
{
    /**
     * @Route("/home/encuesta/{id}", name="encuesta")
     */
    public function indexAction($id)
    {
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();

        $entityManager = $this->getDoctrine()->getManager();
        $encuesta = $entityManager->getRepository(Encuesta::class)->find($id);

        $normalizer->setCircularReferenceHandler(function ($encuesta) {
            return $encuesta->getId();
        });

        $serializer = new Serializer(array($normalizer), array($encoder));
        $jsonContent = $serializer->serialize($encuesta, 'json');

        return $this->render('encuesta/encuesta.html.twig', array(
            'encuesta' => $jsonContent
        ));
    }

    /**
     * @Route ("/home/encuesta/comment/save", name="comentario")
     */
    public function saveCommentAction(Request $request){
        $texto = $request->get('texto');
        $encuesta_ = $request->get('encuesta');


        $entityManager = $this->getDoctrine()->getManager();

        //recupero la encuesta porque no se puede pasar un array, hay que pasarle un objeto Encuesta()
        $encuesta = $entityManager->getRepository(Encuesta::class)->find($encuesta_['id']);


        $comentario = new Comentario();
        $comentario->setText($texto);
        $comentario->setEncuesta($encuesta);

        $entityManager->persist($comentario);
        $entityManager->flush();

        return new Response();
    }

    /**
     * @Route ("/home", name="home")
     */
    public function homeAction(){
        $entityManager = $this->getDoctrine()->getManager();
        $encuestas = $entityManager->getRepository(Encuesta::class)->findBy(array(), array('id' => 'DESC'), 6, 0);

        return $this->render('encuesta/home.html.twig', array('encuestas' => $encuestas));
    }

    /**
     * @Route ("/home/encuestas", name="encuestas")
     */
    public function showEncuestasAction(){
        $entityManager = $this->getDoctrine()->getManager();
        $encuestas = $entityManager->getRepository(Encuesta::class)->findBy(array(), array('id' => 'ASC'), 4, 0);

        $num = $entityManager->getRepository(Encuesta::class)->contarEncuestas();

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

        $entityManager = $this->getDoctrine()->getManager();
        /** @var EncuestaRepository $encuestaRespository */
        $encuestaRespository = $entityManager->getRepository(Encuesta::class);
        $encuestas = $encuestaRespository->findBetween($min, $max);


        //parseamos $encuestas
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();

        $normalizer->setCircularReferenceHandler(function ($encuestas) {
            return $encuestas->getId();
        });

        $serializer = new Serializer(array($normalizer), array($encoder));
        $jsonContent = $serializer->serialize($encuestas, 'json');

        return new JsonResponse($jsonContent);
    }
}
