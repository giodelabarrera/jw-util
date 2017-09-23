<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Hermano;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Class LoadHermano
 * @package AppBundle\DataFixtures\ORM
 */
class LoadHermano extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $privilegios = [];
        $query = $om->getRepository('AppBundle:Privilegio')
            ->createQueryBuilder('p')
            ->getQuery()
        ;
        $iterableResult = $query->iterate();
        foreach ($iterableResult as $row) {
            $privilegio = $row[0];
            $privilegios[$privilegio->getSlug()] = $privilegio;
        }

        $names = [
            [
                'nombre' => 'Giorgio',
                'apellidos' => 'De la Barrera',
                'slug' => 'giorgio-de-la-barrera',
                'email' => 'giodelabarrera@gmail.com',
                'telefono' => '691806134',
                'privilegios' => ['microfono', 'sonido']
            ],
            [
                'nombre' => 'Richard',
                'apellidos' => 'Pin',
                'slug' => 'richard-pin',
                'email' => 'burm20100@hotmail.com',
                'telefono' => '687619326',
                'privilegios' => ['microfono', 'sonido', 'responsable-de-plataforma', 'responsable-de-sonido']
            ],
            [
                'nombre' => 'Jairo',
                'apellidos' => 'Santamaria',
                'slug' => 'jairo-santamaria',
                'privilegios' => ['microfono', 'sonido']
            ],
            [
                'nombre' => 'Joan',
                'apellidos' => 'Aguilere',
                'slug' => 'joan-aguilere',
                'telefono' => '680252682',
                'privilegios' => ['auxiliar-de-plataforma']
            ],
            [
                'nombre' => 'Ivan',
                'apellidos' => 'Martínez',
                'slug' => 'ivan-martinez',
                'privilegios' => ['auxiliar-de-plataforma']
            ],
            [
                'nombre' => 'Antonio',
                'apellidos' => 'Valverde',
                'slug' => 'antonio-valverde',
                'privilegios' => ['microfono']
            ],
            [
                'nombre' => 'Javier',
                'apellidos' => 'Mata',
                'slug' => 'javier-mata',
                'privilegios' => ['microfono']
            ],
            [
                'nombre' => 'Pablo',
                'apellidos' => 'Ortíz',
                'slug' => 'pablo-ortiz',
                'privilegios' => ['microfono']
            ],
            [
                'nombre' => 'Francisco',
                'apellidos' => 'Guerra',
                'slug' => 'francisco-guerra',
                'privilegios' => ['microfono']
            ],
            [
                'nombre' => 'Daniel',
                'apellidos' => 'Yovanovich',
                'slug' => 'daniel-yovanovich',
                'privilegios' => ['microfono']
            ],
            [
                'nombre' => 'Daniel',
                'apellidos' => 'Reyes',
                'slug' => 'daniel-reyes',
                'privilegios' => ['microfono']
            ],
            [
                'nombre' => 'José M',
                'apellidos' => 'Martínez',
                'slug' => 'jose-m-martinez',
                'privilegios' => ['microfono']
            ],
            [
                'nombre' => 'Juan C',
                'apellidos' => 'Cárdenas',
                'slug' => 'juan-c-cardenas',
                'privilegios' => ['microfono']
            ],
            [
                'nombre' => 'Gabriel',
                'apellidos' => 'Galecio',
                'slug' => 'gabriel-galecio',
                'privilegios' => ['microfono']
            ],
            [
                'nombre' => 'Raúl',
                'apellidos' => 'Cárdenas',
                'slug' => 'raul-cardenas',
                'privilegios' => ['microfono']
            ],
            [
                'nombre' => 'Manuel',
                'apellidos' => 'Jiménez',
                'slug' => 'manuel-jimenez',
                'privilegios' => ['microfono']
            ],
            [
                'nombre' => 'Gregorio',
                'apellidos' => 'Castro',
                'slug' => 'gregorio-castro',
                'privilegios' => ['microfono']
            ],
            [
                'nombre' => 'Pedro',
                'apellidos' => 'Santamaría',
                'slug' => 'pedro-santamaria',
                'privilegios' => ['microfono']
            ],
        ];

        foreach ($names as $hermanoMixed) {

            $entity = new Hermano();
            $entity
                ->setNombre($hermanoMixed['nombre'])
                ->setApellidos($hermanoMixed['apellidos'])
                ->setSlug($hermanoMixed['slug'])
            ;

            if (isset($hermanoMixed['email']) && !empty($hermanoMixed['email']))
                $entity->setEmail($hermanoMixed['email']);

            if (isset($hermanoMixed['telefono']) && !empty($hermanoMixed['telefono']))
                $entity->setTelefono($hermanoMixed['telefono']);

            if (isset($hermanoMixed['privilegios']) && !empty($hermanoMixed['privilegios'])) {
                foreach ($hermanoMixed['privilegios'] as $privilegioSlug) {
                    $privilegio = $privilegios[$privilegioSlug];
                    $entity->addPrivilegio($privilegio);
                }
            }

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
        return 20;
    }
}