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
    public function saveAction(){
        $data = [$_POST['texto'], $_POST['encuesta']];

        $entityManager = $this->getDoctrine()->getManager();

        //recupero la encuesta porque no se puede pasar un array, hay que pasarle un objeto Encuesta()
        $encuesta = $entityManager->getRepository(Encuesta::class)->find($_POST['encuesta']['id']);


        $comentario = new Comentario();
        $comentario->setText($data[0]);
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
        $encuestas = $entityManager->getRepository(Encuesta::class)->findAll();

        $size = count($encuestas);

        $encuestas6 = [$encuestas[$size-1], $encuestas[$size-2], $encuestas[$size-3], $encuestas[$size-4], $encuestas[$size-5], $encuestas[$size-6]];

        return $this->render('encuesta/home.html.twig', array('encuestas' => $encuestas6));
    }

    /**
     * @Route ("/home/encuestas", name="encuestas")
     */
    public function showEncuestas(){
        $entityManager = $this->getDoctrine()->getManager();
        $encuestas = $entityManager->getRepository(Encuesta::class)->findAll();

        $size = count($encuestas);
        $min = 1;
        $max = 4;

        $encuestas_3 = [$encuestas[0], $encuestas[1], $encuestas[2], $encuestas[3]];

        return $this->render('encuesta/mostrarEncuestas.html.twig', array('encuestas' => $encuestas_3, 'size' => $size, 'min' => $min, 'max' => $max));
    }

    /**
     * @Route ("/home/encuestas/next-prev", name="encuestasN")
     * @param Request $request
     * @return Response
     */
    public function pagination(Request $request){
        $min = $request->query->get('min');
        $max = $request->query->get('max');

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

        //return new Response($jsonContent);
        return new JsonResponse($jsonContent);
    }
}
