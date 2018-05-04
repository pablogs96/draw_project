<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PreguntaRepository")
 */
class Pregunta
{
    /**
     * Many Preguntas have One Encuesta.
     * @ORM\ManyToOne(targetEntity="Encuesta", inversedBy="preguntas")
     * @ORM\JoinColumn(name="encuesta_id", referencedColumnName="id")
     */
    private $encuesta;

    /**
     * One Pregunta has Many Respuestas.
     * @ORM\OneToMany(targetEntity="Respuesta", mappedBy="pregunta")
     */
    private $respuestas;


    public function __construct() {
        $this->respuestas = new ArrayCollection();
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $text;

    public function getId()
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEncuesta()
    {
        return $this->encuesta;
    }

    /**
     * @param mixed $encuesta
     */
    public function setEncuesta($encuesta): void
    {
        $this->encuesta = $encuesta;
    }

    /**
     * @return mixed
     */
    public function getRespuestas()
    {
        return $this->respuestas;
    }

    /**
     * @param mixed $respuestas
     */
    public function setRespuestas($respuestas): void
    {
        $this->respuestas = $respuestas;
    }

}
