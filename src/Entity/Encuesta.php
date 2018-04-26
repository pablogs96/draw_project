<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EncuestaRepository")
 */
class Encuesta
{
    /**
     * One Encuesta has Many Preguntas.
     * @OneToMany(targetEntity="Pregunta", mappedBy="encuesta")
     */
    private $preguntas;

    /**
     * One Encuesta has Many Resultados.
     * @OneToMany(targetEntity="Resultado", mappedBy="encuesta")
     */
    private $resultados;

    /**
     * One Encuesta has Many Comentarios.
     * @OneToMany(targetEntity="Comentario", mappedBy="encuesta")
     */
    private $comentarios;

    public function __construct() {
        $this->preguntas = new ArrayCollection();
        $this->resultados = new ArrayCollection();
        $this->comentarios = new ArrayCollection();
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
    private $title;

    public function getId()
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }
}
