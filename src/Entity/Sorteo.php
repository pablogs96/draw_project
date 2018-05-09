<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SorteoRepository")
 */
class Sorteo
{
    /**
     * One Sorteo has Many Usuarios.
     * @ORM\OneToMany(targetEntity="Usuario", mappedBy="sorteo")
     */
    private $usuario;

    public function __construct() {
        $this->usuario = new ArrayCollection();
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

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha;


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

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     */
    public function setUsuario($usuario): void
    {
        $this->usuario = $usuario;
    }
}
