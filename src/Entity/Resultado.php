<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ResultadoRepository")
 */
class Resultado
{
    /**
     * Many Resultados have One Encuesta.
     * @ORM\ManyToOne(targetEntity="Encuesta", inversedBy="resultados")
     * @ORM\JoinColumn(name="encuesta_id", referencedColumnName="id", onDelete="CASCADE")
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

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $explanation;

    /**
     * @ORM\Column(type="integer")
     */
    private $minVal;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxVal;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getExplanation(): ?string
    {
        return $this->explanation;
    }

    public function setExplanation(string $explanation): self
    {
        $this->explanation = $explanation;

        return $this;
    }

    public function getMinVal(): ?int
    {
        return $this->minVal;
    }

    public function setMinVal(int $minVal): self
    {
        $this->minVal = $minVal;

        return $this;
    }

    public function getMaxVal(): ?int
    {
        return $this->maxVal;
    }

    public function setMaxVal(int $maxVal): self
    {
        $this->maxVal = $maxVal;

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
