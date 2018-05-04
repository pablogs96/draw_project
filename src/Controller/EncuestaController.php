<?php

namespace App\Controller;

use App\Entity\Comentario;
use App\Entity\Encuesta;
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

    }

    /**
     * @Route ("/home/sorteo", name="sorteo")
     */
    public function sorteoAction(){

    }
}
