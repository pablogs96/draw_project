<?php

namespace App\Entity;

use App\Exceptions\GanadorNotSettedException;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SorteoRepository")
 * @Vich\Uploadable
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

    /**
     * This unmapped property stores the binary contents of the image file
     * associated with the encuesta.
     *
     * @Vich\UploadableField(mapping="encuesta_imgs", fileNameProperty="img")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    private $updatedAt;


    /*************************************************************************************/
    /*************************************************************************************/


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

    public function getImg()
    {
        return $this->img;
    }

    public function setImg($img)
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

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param File $imageFile
     */
    public function setImageFile(File $img = null)
    {
        $this->imageFile = $img;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($img) {
            $this->updatedAt = new \DateTime('now');
        }

    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }



}
