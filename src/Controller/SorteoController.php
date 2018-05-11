<?php

namespace App\Controller;

use App\Entity\Premio;
use App\Entity\Sorteo;
use App\Entity\Usuario;
use App\Repository\SorteoRepository;
use DateInterval;
use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SorteoController extends Controller
{

    /**
     * @Route ("/home/sorteo", name="sorteo")
     */
    public function sorteoAction(){
        $entityManager = $this->getDoctrine()->getManager();

        $sorteos = $entityManager->getRepository(Sorteo::class)->findAll();

        $sorteo = $entityManager->getRepository(Sorteo::class)->findBy(array(), array('fecha' => 'DESC'), 1, 0);
        /** @var Sorteo $sorteo_actual */
        $sorteo_actual = $sorteo[0];

        $id = $sorteo_actual->getId();

        /** @var Sorteo $aux */
        foreach ($sorteos as $aux) {
            if($aux->getId() != $id) {
                $historial [] = $aux;
            }
        }

        $size = count($sorteos);
        $min = 1;
        $max = 4;

        $reverse_historial = array_reverse($historial);

        $hist = [$reverse_historial[0], $reverse_historial[1], $reverse_historial[2], $reverse_historial[3]];

        dump($hist);

        return $this->render('encuesta/sorteo.html.twig', array('historial' => $hist, 'actual' => $sorteo_actual, 'size' => $size, 'min' => $min, 'max' => $max));
    }

    /**
     * @Route ("/home/sorteo/add", name="subscription")
     */
    public function subsciptionAction(){
        //get data from ajax
        $userData = [$_POST['name'], $_POST['mail'], $_POST['pass']];


        //recupero sorteo actual
        $entityManager = $this->getDoctrine()->getManager();
        $sorteo = $entityManager->getRepository(Sorteo::class)->findBy(array(), array('fecha' => 'DESC'), 1, 0);
        // cojo el objeto para no tener que trabajar con el array
        /** @var Sorteo $sorteo_actual */
        $sorteo_actual = $sorteo[0];

        dump($sorteo_actual);


        // fecha de hoy
        /** @var datetime $date */
        $date = new DateTime();
        dump($date);
        // fecha sorteo_actual
        $fecha_sorteo = $sorteo_actual->getFecha();
        dump($fecha_sorteo);


        //añadir, crear o ejecutar
        if ($date < $fecha_sorteo) {
            dump("date<fecha");
            if ($sorteo_actual->getGanador()) {
                // fecha sorteo mayor que la actual y con ganador
                // no deberia de entrar nunca aqui
                return new Response();
            } else {
                // fecha sorteo mayor que la actual y sin ganador
                // añado usuario a sorteo y devuelvo datos actualizados al session
                $this->addUser($userData, $sorteo_actual);
                $respuesta = "Ya estás inscrito al sorteo. ¡Mucha suerte!";
                return new JsonResponse($respuesta);
            }
            } else if ($date >= $fecha_sorteo) {
            dump("date>fecha");
            if ($sorteo_actual->getGanador()){
                //fecha sorteo menor o igual que la actual y con ganador
                // no deberia de entrar
                dump("ERROR 2");
                //creo sorteo
                /** @var Sorteo $newSorteo */
                $newSorteo = $this->createSorteo($fecha_sorteo);
                //añado usuario
                $this->addUser($userData, $newSorteo);
                $respuesta = "Atención: El sorteo anterior ha caducado. Te has inscrito a un nuevo sorteo. ¡Mucha suerte!";
                return new JsonResponse($respuesta);
            } else {
                //fecha sorteo menor o igual que la actual y sin ganador
                //ejecuto sorteo
                $this->runSorteo($sorteo_actual);
                //creo sorteo
                /** @var Sorteo $newSorteo */
                $newSorteo = $this->createSorteo($fecha_sorteo);
                //añado usuario
                $this->addUser($userData, $newSorteo);
                $respuesta = "Atención: El sorteo anterior ha caducado. Te has inscrito a un nuevo sorteo. ¡Mucha suerte! eee";
                return new Response($respuesta);
            }
        }
    }

    /**
     * @Route ("/home/sorteo/historial", name="historial")
     */
    public function historialAction(Request $request) {
        $min = $request->query->get('min');
        $max = $request->query->get('max');

        dump($min);
        dump($max);

        $entityManager = $this->getDoctrine()->getManager();
        /** @var SorteoRepository $sorteoRespository */
        $sorteoRespository = $entityManager->getRepository(Sorteo::class);
        $sorteos = $sorteoRespository->findBetween($min, $max);


        //parseamos $encuestas
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();

        $normalizer->setCircularReferenceHandler(function ($sorteos) {
            return $sorteos->getId();
        });

        $serializer = new Serializer(array($normalizer), array($encoder));
        $jsonContent = $serializer->serialize($sorteos, 'json');

        dump($jsonContent);
        return new JsonResponse($jsonContent);
    }

    private function addUser($data, Sorteo $sorteoActual){
        $newUser = new Usuario();
        $newUser->setNombre($data[0]);
        $newUser->setEmail($data[1]);
        $newUser->setPassword($data[2]);
        $newUser->setsorteo($sorteoActual);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($newUser);
        $entityManager->flush();

    }

    private function createSorteo($fecha_sorteo){
        try {
            $newFecha = $fecha_sorteo->add(new DateInterval('P1M'));

            $entityManager = $this->getDoctrine()->getManager();

            $premios = $entityManager->getRepository(Premio::class)->findAll();
            /** @var Premio $randomPremio */
            $randomPremio = $premios[rand(0, count($premios) - 1)];

            $newSorteo = new Sorteo();
            $newSorteo->setPremio($randomPremio->getTitle());
            $newSorteo->setImg($randomPremio->getImagen());
            $newSorteo->setFecha($newFecha);

            $entityManager->persist($newSorteo);
            $entityManager->flush();

            return $newSorteo;
        } catch (\Exception $e) {
            dump($e->getMessage());
        }
    }

    private function runSorteo(Sorteo $sorteo_actual) {
        $entityManager = $this->getDoctrine()->getManager();

        $users = $entityManager->getRepository(Usuario::class)->findBy(array('sorteo' => $sorteo_actual));

        /** @var Usuario $userWinner */
        $userWinner = $users[rand(0, count($users) - 1)];
        $sorteo_actual->setGanador($userWinner->getNombre());
        }
}
