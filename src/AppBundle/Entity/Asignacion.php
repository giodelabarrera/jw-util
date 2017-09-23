<?php

namespace AppBundle\Entity;

use AppBundle\Model\Entity\DateStampEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Asignacion
 *
 * @ORM\Table(
 *     name="asignacion",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(columns={"hermano_id", "privilegio_id", "fecha"})
 *     }
 * )
 * @UniqueEntity(fields={"hermano", "privilegio", "fecha"})
 * @UniqueEntity(fields={"privilegio", "fecha", "hermano"})
 * @UniqueEntity(fields={"fecha", "hermano", "privilegio"})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AsignacionRepository")
 */
class Asignacion extends DateStampEntity
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
     * @var Hermano
     *
     * @ORM\ManyToOne(targetEntity="Hermano")
     * @ORM\JoinColumn(name="hermano_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     *
     * @Assert\NotBlank()
     */
    private $hermano;

    /**
     * @var Privilegio
     *
     * @ORM\ManyToOne(targetEntity="Privilegio")
     * @ORM\JoinColumn(name="privilegio_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     *
     * @Assert\NotBlank()
     */
    private $privilegio;

    /**
     * @var \Datetime
     *
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
     * Asignacion constructor.
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
        return (string)$this->id;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Asignacion
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set hermano
     *
     * @param \AppBundle\Entity\Hermano $hermano
     *
     * @return Asignacion
     */
    public function setHermano(\AppBundle\Entity\Hermano $hermano)
    {
        $this->hermano = $hermano;

        return $this;
    }

    /**
     * Get hermano
     *
     * @return \AppBundle\Entity\Hermano
     */
    public function getHermano()
    {
        return $this->hermano;
    }

    /**
     * Set privilegio
     *
     * @param \AppBundle\Entity\Privilegio $privilegio
     *
     * @return Asignacion
     */
    public function setPrivilegio(\AppBundle\Entity\Privilegio $privilegio)
    {
        $this->privilegio = $privilegio;

        return $this;
    }

    /**
     * Get privilegio
     *
     * @return \AppBundle\Entity\Privilegio
     */
    public function getPrivilegio()
    {
        return $this->privilegio;
    }
}
