<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PremioRepository")
 */
class Premio
{
    /**
     * One Premio has Many Sorteos.
     * @ORM\OneToMany(targetEntity="Sorteo", mappedBy="premio")
     */
    private $sorteos;

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
     * @return mixed
     */
    public function getSorteos()
    {
        return $this->sorteos;
    }

    /**
     * @param mixed $sorteos
     */
    public function setSorteos($sorteos): void
    {
        $this->sorteos = $sorteos;
    }

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imagen;

    public function __toString()
    {
        return $this->title;
    }

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

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(string $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }
}
