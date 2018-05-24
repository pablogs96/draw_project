<?php
/**
 * Created by PhpStorm.
 * User: pablo
 * Date: 24/05/18
 * Time: 12:36
 */

namespace App\Services;


use App\Entity\Comentario;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;

class EncuestaService
{
    private $entityManager;
    private $logger;
    private $encuestaClass;

    public function __construct(EntityManager $entityManager, $encuestaClass, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->encuestaClass = $encuestaClass;
    }

    public function getEncuestaById($id)
    {
        return $this->entityManager->getRepository($this->encuestaClass)->find($id);
    }

    public function saveCommentInEncuesta($comment, $encuesta)
    {
        //recupero la encuesta porque no se puede pasar un array, hay que pasarle un objeto Encuesta()
        $encuesta = $this->entityManager->getRepository($this->encuestaClass)->find($encuesta['id']);


        $comentario = new Comentario();
        $comentario->setText($comment);
        $comentario->setEncuesta($encuesta);

        try{
            $this->entityManager->persist($comentario);
            $this->entityManager->flush();
            return true;
        } catch (ORMException $e) {
            $this->logger->alert($e->getMessage());
            return false;
        }
    }

    public function getEncuestasOrderby($criteria, $order, $limit, $offset)
    {
        return $this->entityManager->getRepository($this->encuestaClass)->findBy($criteria, $order, $limit, $offset);
    }

    public function getTotalEncuestas()
    {
        return $this->entityManager->getRepository($this->encuestaClass)->contarEncuestas();
    }

    public function getEncuestasBetween($min, $max)
    {
        return $this->entityManager->getRepository($this->encuestaClass)->findBetween($min, $max);
    }
}