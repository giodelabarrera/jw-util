<?php

namespace AppBundle\Entity;

use AppBundle\Model\Entity\DateStampEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Hermano
 *
 * @ORM\Table(name="hermano")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\HermanoRepository")
 * @UniqueEntity("slug")
 */
class Hermano extends DateStampEntity
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
     * @ORM\Column(name="apellidos", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $apellidos;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     * @Gedmo\Slug(fields={"nombre", "apellidos"}, updatable=false)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=255, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\ManyToMany(targetEntity="Privilegio", inversedBy="hermanos")
     * @ORM\JoinTable(
     *     name="hermano_privilegio",
     *     joinColumns={@ORM\JoinColumn(name="hermano_id", referencedColumnName="id", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="privilegio_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     */
    private $privilegios;


    /**
     * Hermano constructor.
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
        return $this->nombre.' '.$this->apellidos;
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
     * @return Hermano
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
     * Set apellidos
     *
     * @param string $apellidos
     *
     * @return Hermano
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Hermano
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
     * Set email
     *
     * @param string $email
     *
     * @return Hermano
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return Hermano
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Add privilegio
     *
     * @param \AppBundle\Entity\Privilegio $privilegio
     *
     * @return Hermano
     */
    public function addPrivilegio(\AppBundle\Entity\Privilegio $privilegio)
    {
        $this->privilegios[] = $privilegio;

        return $this;
    }

    /**
     * Remove privilegio
     *
     * @param \AppBundle\Entity\Privilegio $privilegio
     */
    public function removePrivilegio(\AppBundle\Entity\Privilegio $privilegio)
    {
        $this->privilegios->removeElement($privilegio);
    }

    /**
     * Get privilegios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPrivilegios()
    {
        return $this->privilegios;
    }
}
