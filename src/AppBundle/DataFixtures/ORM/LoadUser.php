<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Class LoadUser
 * @package AppBundle\DataFixtures\ORM
 */
class LoadUser extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
                'name' => 'Giorgio',
                'surname' => 'De la Barrera',
                'username' => 'giorgiodelabarrera',
                'email' => 'giodelabarrera@gmail.com',
                'password' => '12345678',
                'phone' => '',
                'roles' => ['ROLE_SUPER_ADMIN'],
            ],
        ];


        $userManager = $this->container->get('app.user_manager');

        foreach ($names as $userMixed) {

            $entity = new User();
            $entity
                ->setName($userMixed['name'])
                ->setSurname($userMixed['surname'])
                ->setUsername($userMixed['username'])
                ->setPlainPassword($userMixed['password'])
            ;

            if (isset($userMixed['email']) && !empty($userMixed['email']))
                $entity->setEmail($userMixed['email']);

            if (isset($userMixed['phone']) && !empty($userMixed['phone']))
                $entity->setPhone($userMixed['phone']);

            if (isset($userMixed['roles']) && !empty($userMixed['roles']))
                $entity->setRoles($userMixed['roles']);

            $userManager->save($entity);
        }
    }


    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }
}