<?php
/**
 * Created by PhpStorm.
 * User: pablo
 * Date: 24/05/18
 * Time: 15:16
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class BaseController extends Controller
{
    protected function serializar($object) {
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();

        // esto se utiliza cuando serializamos para que no de una referencia circular
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

        $serializer = new Serializer(array($normalizer), array($encoder));
        $jsonContent = $serializer->serialize($object, 'json');

        return $jsonContent;
    }
}