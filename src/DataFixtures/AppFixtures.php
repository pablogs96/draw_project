<?php
/**
 * Created by PhpStorm.
 * User: pablogarcia
 * Date: 25/04/18
 * Time: 15:14
 */

namespace App\DataFixtures;

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
        }

        // create 8 preguntas
        for ($i = 1; $i <= 8; $i++) {
            foreach ($Pimages as $img) {
                $pregunta = new Pregunta();
                $pregunta->setImage($img);
                $pregunta->setText('pregunta '.$i);
                $manager->persist($pregunta);
            }
        }

        // create 32 respuestas
        for ($i = 1; $i <= 32; $i++) {
            $respuesta = new Respuesta();
            $respuesta->setText('Respuesta '.$i);
            $respuesta->setvalue(mt_rand(0, 10));
            $manager->persist($respuesta);
        }

        //create 32*3 resultados (96) resultados
        for ($i = 1; $i < 96; $i++){
            $resultado = new Resultado();
            $resultado->setText('Resultado '.$i);
            $resultado->setImage('http://images.yodibujo.es/_uploads/_tiny_galerie/20130414/lisa-simpson-hija_6zs.jpg');
            $resultado->setExplanation('Explanation '.$i);
            $resultado->setMinVal(mt_rand(0, 10));
            $resultado->setMaxVal(mt_rand(10, 20));
            $manager->persist($resultado);
        }


        $manager->flush();
    }
}