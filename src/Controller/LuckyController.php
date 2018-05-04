<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LuckyController extends Controller
{
	/**
      * @Route("/lucky/number/{id}", name="example")
      */
    public function number($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $encuesta = $entityManager->getRepository(Encuesta::class)->find($id);

        return $this->render('lucky/number.html.twig', array(
            'encuesta' => $encuesta
        ));
    }
}