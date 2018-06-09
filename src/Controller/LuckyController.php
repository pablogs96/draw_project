<?php  // ESTE CÓDIGO NO ES DEL PROYECTO FIN DE CICLO
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LuckyController extends Controller
{
	/**
      * @Route("/pizarra", name="example")
      */
    public function number()
    {
//        $entityManager = $this->getDoctrine()->getManager();
//        $encuesta = $entityManager->getRepository(Encuesta::class)->find($id);

        return $this->render('basic.html'
        );
    }
}