<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PreguntaRepository")
 * @Vich\Uploadable
 */
class Pregunta
{
    /**
     * Many Preguntas have One Encuesta.
     * @ORM\ManyToOne(targetEntity="Encuesta", inversedBy="preguntas")
     * @ORM\JoinColumn(name="encuesta_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $encuesta;

    /**
     * One Pregunta has Many Respuestas.
     * @ORM\OneToMany(targetEntity="Respuesta", mappedBy="pregunta")
     */
    private $respuestas;


    public function __construct() {
        $this->respuestas = new ArrayCollection();
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
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $text;

    /**
     * This unmapped property stores the binary contents of the image file
     * associated with the encuesta.
     *
     * @Vich\UploadableField(mapping="encuesta_imgs", fileNameProperty="image")
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

    public function __toString()
    {
        $aux = (string)$this->id;
        return $aux;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
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

    /**
     * @return mixed
     */
    public function getRespuestas()
    {
        return $this->respuestas;
    }

    /**
     * @param mixed $respuestas
     */
    public function setRespuestas($respuestas): void
    {
        $this->respuestas = $respuestas;
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
