<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Asignacion;
use AppBundle\Entity\Privilegio;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Class LoadPrivilegio
 * @package AppBundle\DataFixtures\ORM
 */
class LoadPrivilegio extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var
     */
    private $container;

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $om
     */
    public function load(ObjectManager $om)
    {
        $names = [
            [
                'name' => 'Micrófono',
                'slug' => 'microfono',
            ],
            [
                'name' => 'Sonido',
                'slug' => 'sonido',
            ],
            [
                'name' => 'Responsable de plataforma',
                'slug' => 'responsable-de-plataforma',
            ],
            [
                'name' => 'Auxiliar de plataforma',
                'slug' => 'auxiliar-de-plataforma',
            ],
            [
                'name' => 'Responsable de sonido',
                'slug' => 'responsable-de-sonido',
            ],
        ];


        foreach ($names as $nameMixed) {

            $entity = new Privilegio();
            $entity->setNombre($nameMixed['name']);
            $entity->setSlug($nameMixed['slug']);

            $om->persist($entity);
        }

        $om->flush();
    }


    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 10;
    }
}