<?php
/**
 * Created by PhpStorm.
 * User: pablo
 * Date: 30/05/18
 * Time: 10:37
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class CorreccionExamenController extends Controller
{
    /**
     * @Route ("/correccion"), name="correccion"
     */
    public function indexAction()
    {
        return $this->render('examCorrection/basic.html.twig');
    }
}