<?php

namespace AppBundle\Entity;

use AppBundle\Model\Entity\DateStampEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Privilegio
 *
 * @ORM\Table(name="privilegio")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PrivilegioRepository")
 * @UniqueEntity("slug")
 */
class Privilegio extends DateStampEntity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     * @Gedmo\Slug(fields={"nombre"}, updatable=false)
     */
    private $slug;

    /**
     * @ORM\ManyToMany(targetEntity="Hermano", mappedBy="privilegios")
     */
    private $hermanos;


    /**
     * Privilegio constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->nombre;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Privilegio
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Privilegio
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add hermano
     *
     * @param \AppBundle\Entity\Hermano $hermano
     *
     * @return Privilegio
     */
    public function addHermano(\AppBundle\Entity\Hermano $hermano)
    {
        $this->hermanos[] = $hermano;

        return $this;
    }

    /**
     * Remove hermano
     *
     * @param \AppBundle\Entity\Hermano $hermano
     */
    public function removeHermano(\AppBundle\Entity\Hermano $hermano)
    {
        $this->hermanos->removeElement($hermano);
    }

    /**
     * Get hermanos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHermanos()
    {
        return $this->hermanos;
    }
}
