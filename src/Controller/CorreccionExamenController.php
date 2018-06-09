<?php   // ESTE CÓDIGO NO ES DEL PROYECTO FIN DE CICLO
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

    /**
     * @Route ("/styles"), name="styles"
     */
    public function stylesAction()
    {
        return $this->render('examCorrection/styles.html.twig');
    }
}