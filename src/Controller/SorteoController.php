<?php

namespace App\Controller;

use App\Entity\Sorteo;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SorteoController extends Controller
{

    /**
     * @Route ("/home/sorteo", name="sorteo")
     */
    public function sorteoAction(){
        return $this->render('encuesta/sorteo.html.twig');
    }

    /**
     * @Route ("/home/sorteo/add", name="subscription")
     */
    public function subsciption(){

        //get data from ajax
        $userData = [$_POST['name'], $_POST['mail'], $_POST['pass']];

        //recupero sorteo actual
        $entityManager = $this->getDoctrine()->getManager();
//        $sorteo_actual = $entityManager->getRepository(Sorteo::class)->findBy([], ['fecha' => 'DESC'], 1, 0);
        $sorteos = $entityManager->getRepository(Sorteo::class)->findAll();

        // get date
        $date = getdate();

        // get sorteo date
        /** @var Sorteo $sorteo_actual */
        $sorteo_actual = $sorteos[count($sorteos) - 1];
        $fecha_sorteo = $sorteo_actual->getFecha();

        // compruebo que la fecha actual es menor que la del sorteo actual
        if ($date < $fecha_sorteo) {
            if ($sorteo_actual->getGanador()) {
                //creo sorteo
            } else {
                // ejecuto randomSorteo y añado ganador
                // creo un sorteo nuevo
                // mando a la vista sorteo actual e historico de sorteos
                dump("creo sorteo");
            }
        } else {
            // inscribo al usuario
            // mando a la vista sorteo actual actualizado
        }


    }
    /*$data = [$_POST['texto'], $_POST['encuesta']];

        $entityManager = $this->getDoctrine()->getManager();

        //recupero la encuesta porque no se puede pasar un array, hay que pasarle un objeto Encuesta()
        $encuesta = $entityManager->getRepository(Encuesta::class)->find($_POST['encuesta']['id']);


        $comentario = new Comentario();
        $comentario->setText($data[0]);
        $comentario->setEncuesta($encuesta);

        $entityManager->persist($comentario);
        $entityManager->flush();

        return new Response();*/
}
