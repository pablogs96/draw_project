<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity(repositoryClass="App\Repository\EncuestaRepository")
 * @Vich\Uploadable
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


    /**
     * This unmapped property stores the binary contents of the image file
     * associated with the product.
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

    public function getImg()
    {
        return $this->img;
    }

    public function setImg(string $img)
    {
        $this->img = $img;

        return $this;
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
