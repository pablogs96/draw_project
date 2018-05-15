<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Usuario", inversedBy="sorteos")
     */
    private $usuarios;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario", inversedBy="sorteos_ganados")
     */
    private $ganador;

    public function __construct()
    {
        $this->usuarios = new ArrayCollection();
    }


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
     * @return Collection|Usuario[]
     */
    public function getUsuarios(): Collection
    {
        return $this->usuarios;
    }

    public function addUsuario(Usuario $usuario): self
    {
        if (!$this->usuarios->contains($usuario)) {
            $this->usuarios[] = $usuario;
        }

        return $this;
    }

    public function removeUsuario(Usuario $usuario): self
    {
        if ($this->usuarios->contains($usuario)) {
            $this->usuarios->removeElement($usuario);
        }

        return $this;
    }

    public function getGanador(): ?Usuario
    {
        return $this->ganador;
    }

    public function setGanador(?Usuario $ganador): self
    {
        $this->ganador = $ganador;

        return $this;
    }
}
