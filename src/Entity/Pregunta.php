<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PreguntaRepository")
 */
class Pregunta
{
    /**
     * Many Preguntas have One Encuesta.
     * @ManyToOne(targetEntity="Encuesta", inversedBy="preguntas")
     * @JoinColumn(name="encuesta_id", referencedColumnName="id")
     */
    private $encuesta;

    /**
     * One Pregunta has Many Respuestas.
     * @OneToMany(targetEntity="Respuesta", mappedBy="pregunta")
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
}
