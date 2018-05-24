<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ComentarioRepository")
 */
class Comentario
{
    /**
     * Many Comentarios have One Encuesta.
     * @ORM\ManyToOne(targetEntity="Encuesta", inversedBy="comentarios")
     * @ORM\JoinColumn(name="encuesta_id", referencedColumnName="id")
     */
    private $encuesta;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $text;

    public function __toString()
    {
        return $this->text;
    }

    public function getId()
    {
        return $this->id;
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

}
