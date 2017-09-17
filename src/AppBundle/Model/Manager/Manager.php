<?php

namespace AppBundle\Model\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping as ORM;


/**
 * Class Manager
 * @package AppBundle\Model\Manager
 */
abstract class Manager
{
    /**
     * @var ObjectManager
     */
    protected $om;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $repository;

    /**
     * Manager constructor.
     * @param ObjectManager $om
     * @param $repositoryClass
     */
    public function __construct(ObjectManager $om, $repositoryClass)
    {
        $this->om         = $om;
        $this->repository = $om->getRepository($repositoryClass);
    }
}
