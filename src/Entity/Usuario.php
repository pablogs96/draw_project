<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsuarioRepository")
 */
class Usuario
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
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Sorteo", mappedBy="usuarios")
     */
    private $sorteos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sorteo", mappedBy="ganador")
     */
    private $sorteos_ganados;

    public function __construct()
    {
        $this->sorteos = new ArrayCollection();
        $this->sorteos_ganados = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->email;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection|Sorteo[]
     */
    public function getSorteos(): Collection
    {
        return $this->sorteos;
    }

    public function addSorteo(Sorteo $sorteo): self
    {
        if (!$this->sorteos->contains($sorteo)) {
            $this->sorteos[] = $sorteo;
            $sorteo->addUsuario($this);
        }

        return $this;
    }

    public function removeSorteo(Sorteo $sorteo): self
    {
        if ($this->sorteos->contains($sorteo)) {
            $this->sorteos->removeElement($sorteo);
            $sorteo->removeUsuario($this);
        }

        return $this;
    }

    /**
     * @return Collection|Sorteo[]
     */
    public function getSorteosGanados(): Collection
    {
        return $this->sorteos_ganados;
    }

    public function addSorteosGanado(Sorteo $sorteosGanado): self
    {
        if (!$this->sorteos_ganados->contains($sorteosGanado)) {
            $this->sorteos_ganados[] = $sorteosGanado;
            $sorteosGanado->setGanador($this);
        }

        return $this;
    }

    public function removeSorteosGanado(Sorteo $sorteosGanado): self
    {
        if ($this->sorteos_ganados->contains($sorteosGanado)) {
            $this->sorteos_ganados->removeElement($sorteosGanado);
            // set the owning side to null (unless already changed)
            if ($sorteosGanado->getGanador() === $this) {
                $sorteosGanado->setGanador(null);
            }
        }

        return $this;
    }
}
