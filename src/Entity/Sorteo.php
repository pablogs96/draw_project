<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SorteoRepository")
 */
class Sorteo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $img;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $premio;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numero_usuarios;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ganador;

    public function getId()
    {
        return $this->id;
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

    public function getPremio(): ?string
    {
        return $this->premio;
    }

    public function setPremio(string $premio): self
    {
        $this->premio = $premio;

        return $this;
    }

    public function getNumeroUsuarios(): ?int
    {
        return $this->numero_usuarios;
    }

    public function setNumeroUsuarios(?int $numero_usuarios): self
    {
        $this->numero_usuarios = $numero_usuarios;

        return $this;
    }

    public function getGanador(): ?string
    {
        return $this->ganador;
    }

    public function setGanador(?string $ganador): self
    {
        $this->ganador = $ganador;

        return $this;
    }
}
