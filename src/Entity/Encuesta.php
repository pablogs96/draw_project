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
     * @ORM\OneToMany(targetEntity="Pregunta", mappedBy="encuesta")
     */
    private $preguntas;

    /**
     * One Encuesta has Many Resultados.
     * @ORM\OneToMany(targetEntity="Resultado", mappedBy="encuesta")
     */
    private $resultados;

    /**
     * One Encuesta has Many Comentarios.
     * @ORM\OneToMany(targetEntity="Comentario", mappedBy="encuesta")
     */
    private $comentarios;

    public function __construct() {
        $this->preguntas = new ArrayCollection();
        $this->resultados = new ArrayCollection();
        $this->comentarios = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title;
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

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $img;

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

    /**
     * @return mixed
     */
    public function getPreguntas()
    {
        return $this->preguntas;
    }

    /**
     * @param mixed $preguntas
     */
    public function setPreguntas($preguntas): void
    {
        $this->preguntas = $preguntas;
    }

    /**
     * @return mixed
     */
    public function getResultados()
    {
        return $this->resultados;
    }

    /**
     * @param mixed $resultados
     */
    public function setResultados($resultados): void
    {
        $this->resultados = $resultados;
    }

    /**
     * @return mixed
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * @param mixed $comentarios
     */
    public function setComentarios($comentarios): void
    {
        $this->comentarios = $comentarios;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): self
    {
        $this->img = $img;

        return $this;
    }

}
