<?php
/**
 * Created by PhpStorm.
 * User: pablogarcia
 * Date: 25/04/18
 * Time: 15:14
 */

namespace App\DataFixtures;

use App\Entity\Comentario;
use App\Entity\Encuesta;
use App\Entity\Pregunta;
use App\Entity\Respuesta;
use App\Entity\Resultado;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $Pimages = ["https://desempleotecnologico.files.wordpress.com/2013/07/work-relax.jpeg?w=500",
                    "http://www.quieroimagenes.com/i/Homer-trabajando-imagen.jpg",
                    "https://unaodoscopas.files.wordpress.com/2016/07/o_marcalpena_moes.jpg",
                    "https://vignette.wikia.nocookie.net/inciclopedia/images/d/d7/Lisa_simpson_1.gif/revision/latest?cb=20090202033921",
                    "http://www.puzzlesonline.es/puzzles/2015/simpsons/tirachinasdebart.jpg",
                    "http://www.freakingnews.com/pictures/37000/Homer-Asleep-on-a-Sofa-37122.jpg",
                    "http://fotologimg.s3.amazonaws.com/photo/31/25/84/skate_sur/1228481038412_f.jpg",
                    "https://vignette.wikia.nocookie.net/lossimpson/images/1/14/Ralph_Wiggum.png/revision/latest?cb=20150426070659&path-prefix=es"
            ];

        // create 2 encuestas
        for ($i = 1; $i <= 2; $i++) {
            $encuesta = new Encuesta();
            $encuesta->setTitle('Encuesta '.$i);
            $manager->persist($encuesta);
            for ($j = 1; $j <= 4; $j++) {
                $pregunta = new Pregunta();
                $pregunta->setImage($Pimages[0]);
                $pregunta->setText('pregunta '. $j);
                $pregunta->setEncuesta($encuesta);
                $manager->persist($pregunta);
                for ($h = 1; $h <= 4; $h++) {
                    $respuesta = new Respuesta();
                    $respuesta->setText('Respuesta '.$h.' de la pregunta '.$j.' de la encuesta '.$i);
                    $respuesta->setvalue(mt_rand(0, 5));
                    $respuesta->setPregunta($pregunta);
                    $manager->persist($respuesta);
                }
            }
            for ($j = 1; $j <= 3; $j++){
                $resultado = new Resultado();
                $resultado->setText('Resultado '.$j);
                $resultado->setImage('http://images.yodibujo.es/_uploads/_tiny_galerie/20130414/lisa-simpson-hija_6zs.jpg');
                $resultado->setExplanation('Explanation '.$j);
                $resultado->setMinVal(mt_rand(0, 10));
                $resultado->setMaxVal(mt_rand(10, 20));
                $resultado->setEncuesta($encuesta);
                $manager->persist($resultado);
            }
            for ($j = 1; $j <= 4; $j++){
                $coment = new Comentario();
                $coment->setText('Comentario '.$j.' de la encuesta '.$i);
                $coment->setEncuesta($encuesta);
                $manager->persist($coment);
            }
        }
        $manager->flush();
    }
}