<?php

namespace App\Entity;

use App\Exceptions\GanadorNotSettedException;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SorteoRepository")
 */
class Sorteo
{
    /**
     * Many Sorteos have One Premio.
     * @ORM\ManyToOne(targetEntity="Premio", inversedBy="sorteos")
     * @ORM\JoinColumn(name="premio_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $premio;

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

    public function __toString()
    {
        return (string)$this->id;
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

    /**
     * @return mixed
     */
    public function getPremio()
    {
        return $this->premio;
    }

    /**
     * @param mixed $premio
     */
    public function setPremio($premio): void
    {
        $this->premio = $premio;
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

    /**
     * @param Usuario|null $ganador
     * @return Sorteo
     * @throws GanadorNotSettedException
     */
    public function setGanador(?Usuario $ganador): self
    {
        $hoy = new \DateTime();

        if ($this->getFecha() > $hoy) {
            throw new GanadorNotSettedException('No se ha podido añadir el ganador al sorteo '.$this->id.'. Fecha de sorteo > actual.');
        } else {
            $this->ganador = $ganador;
            return $this;
        }

    }
}
