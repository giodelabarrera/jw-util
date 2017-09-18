<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Asignacion;
use AppBundle\Entity\Departamento;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Class LoadAsignacion
 * @package AppBundle\DataFixtures\ORM
 */
class LoadAsignacion extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $users = [];
        $query = $om->getRepository('AppBundle:User')
            ->createQueryBuilder('u')
            ->getQuery()
            ;
        $iterableResult = $query->iterate();
        foreach ($iterableResult as $row) {
            $user = $row[0];
            $users[$user->getUsername()] = $user;
        }

        $departamentos = [];
        $query = $om->getRepository('AppBundle:Departamento')
            ->createQueryBuilder('d')
            ->getQuery()
            ;
        $iterableResult = $query->iterate();
        foreach ($iterableResult as $row) {
            $departamento = $row[0];
            $departamentos[$departamento->getSlug()] = $departamento;
        }

        $list = [
            'microfono' => [
                'usuarios' => [
                    [
                        'usuario' => 'giorgiodelabarrera',
                        'cargo' => '',
                    ],
                    [
                        'usuario' => 'richardpin',
                        'cargo' => 'Responsable de plataforma',
                    ],
                    [
                        'usuario' => 'jairosantamaria',
                        'cargo' => '',
                    ],
                    [
                        'usuario' => 'joanaguilere',
                        'cargo' => 'Auxiliar de plataforma',
                    ],
                    [
                        'usuario' => 'ivanmartinez',
                        'cargo' => 'Auxiliar de plataforma',
                    ],
                    [
                        'usuario' => 'antoniovalverde',
                        'cargo' => '',
                    ],
                    [
                        'usuario' => 'javiermata',
                        'cargo' => '',
                    ],
                    [
                        'usuario' => 'pabloortiz',
                        'cargo' => '',
                    ],
                    [
                        'usuario' => 'franciscoguerra',
                        'cargo' => '',
                    ],
                    [
                        'usuario' => 'danielyovanovich',
                        'cargo' => '',
                    ],
                    [
                        'usuario' => 'danielreyes',
                        'cargo' => '',
                    ],
                    [
                        'usuario' => 'josemmartinez',
                        'cargo' => '',
                    ],
                    [
                        'usuario' => 'juanccardenas',
                        'cargo' => '',
                    ],
                    [
                        'usuario' => 'gabrielgalecio',
                        'cargo' => '',
                    ],
                    [
                        'usuario' => 'raulcardenas',
                        'cargo' => '',
                    ],
                    [
                        'usuario' => 'manueljimenez',
                        'cargo' => '',
                    ],
                    [
                        'usuario' => 'gregoriocastro',
                        'cargo' => '',
                    ],
                    [
                        'usuario' => 'pedrosantamaria',
                        'cargo' => '',
                    ],
                ],
            ],
            'sonido' => [
                'usuarios' => [
                    [
                        'usuario' => 'giorgiodelabarrera',
                        'cargo' => '',
                    ],
                    [
                        'usuario' => 'richardpin',
                        'cargo' => 'Responsable de sonido',
                    ],
                    [
                        'usuario' => 'jairosantamaria',
                        'cargo' => '',
                    ],
                ]
            ]
        ];


        foreach ($list as $departamentoNombre => $departamentoMixed) {

            foreach ($departamentoMixed['usuarios'] as $usuarioMixed) {

                $entity = new Asignacion();

                $user = $users[$usuarioMixed['usuario']];
                $entity->setUser($user);

                $departamento = $departamentos[$departamentoNombre];
                $entity->setDepartamento($departamento);

                if (isset($usuarioMixed['cargo']) && !empty($usuarioMixed['cargo']))
                    $entity->setCargo($usuarioMixed['cargo']);

                $om->persist($entity);
            }
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