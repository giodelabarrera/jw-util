<?php

namespace AppBundle\Manager;

use AppBundle\Entity\User;
use AppBundle\Model\Manager\CRUDManagerInterface;
use AppBundle\Model\Manager\Manager;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\Form;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * Class UserManager
 *
 * @package AppBundle\Manager
 */
class UserManager extends Manager implements CRUDManagerInterface
{
    const REPOSITORY_CLASS = 'AppBundle:User';

    protected $encoderFactory;

    protected $tokenStorage;

    public function __construct(EncoderFactoryInterface $encoderFactory, ObjectManager $om, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($om, self::REPOSITORY_CLASS);
        $this->encoderFactory = $encoderFactory;
        $this->tokenStorage   = $tokenStorage;
    }

    /**
     * {@inheritDoc}
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * getFilterQueryBuilder
     *
     * @return QueryBuilder
     */
    public function getFilterQueryBuilder()
    {
        $loggedUser = $this->tokenStorage->getToken()->getUser();

        $filterBuilder = $this->om
            ->getRepository(self::REPOSITORY_CLASS)
            ->createQueryBuilder('u')
            ->select('u')
            ->orderBy('u.id', 'ASC')
        ;

        if (!$loggedUser->hasRole('ROLE_SUPER_ADMIN')) {
            $filterBuilder
                ->andWhere('cl.id = '.$loggedUser->getClient()->getId())
                ->andWhere('u.roles NOT LIKE \'%ROLE_SUPER_ADMIN%\'')
            ;
        }

        return $filterBuilder;
    }

    /**
     * addFilterConditions
     *
     * @param Form $filterForm
     * @param QueryBuilder $filterBuilder
     *
     * @return QueryBuilder
     */
    public function addFilterConditions(Form $filterForm, QueryBuilder $filterBuilder)
    {
        $loggedUser = $this->tokenStorage->getToken()->getUser();

        /*if ($loggedUser->hasRole('ROLE_SUPER_ADMIN')) {
            // client
            if ($filterForm->getData()['client']) {
                $filterBuilder
                    ->andWhere('cl.id = :client')
                    ->setParameter('client', $filterForm->getData()['client']);
            }
        }*/
        // id
        if ($filterForm->getData()['id']) {
            $filterBuilder
                ->andWhere('u.id = :id')
                ->setParameter('id', $filterForm->getData()['id']);
        }
        // name
        if ($filterForm->getData()['name']) {
            $filterBuilder
                ->andWhere('u.name like :name')
                ->setParameter('name', '%'.$filterForm->getData()['name'].'%');
        }
        // surname
        if ($filterForm->getData()['surname']) {
            $filterBuilder
                ->andWhere('u.surname like :surname')
                ->setParameter('surname', '%'.$filterForm->getData()['surname'].'%');
        }
        // username
        if ($filterForm->getData()['username']) {
            $filterBuilder
                ->andWhere('u.username like :username')
                ->setParameter('username', '%'.$filterForm->getData()['username'].'%');
        }
        /*// email
        if ($filterForm->getData()['email']) {
            $filterBuilder
                ->andWhere('u.email like :email')
                ->setParameter('email', '%'.$filterForm->getData()['email'].'%');
        }*/
        // roles
        if ($filterForm->getData()['roles']) {
            $roles = $filterForm->getData()['roles'];

            if (!count($roles) == 0) {
                $whereCondition = '';
                $i = 0;
                foreach ($roles as $role) {
                    if ($i == 0) {
                        $whereCondition .= 'u.roles LIKE \'%'.$role.'%\'';
                    } else {
                        $whereCondition .= ' OR u.roles LIKE \'%'.$role.'%\'';
                    }
                    $i++;
                }
                $filterBuilder
                    ->andWhere($whereCondition);
            }
        }
        /*// activo
        $filterBuilder
            ->andWhere('u.activo = :activo')
            ->setParameter('activo', $filterForm->getData()['activo']);*/

        // created
        if ($filterForm->getData()['created']) {
            $filterBuilder
                ->andWhere('u.created like :created')
                ->setParameter('created', '%'.$filterForm->getData()['created']->format('Y-m-d').'%');
        }
        // updated
        if ($filterForm->getData()['updated']) {
            $filterBuilder
                ->andWhere('u.updated like :updated')
                ->setParameter('updated', '%'.$filterForm->getData()['updated']->format('Y-m-d').'%');
        }

        return $filterBuilder;
    }

    /**
     * createBasicUser
     *
     *
     * @param $username
     * @param $email
     * @param $password
     * @param bool|false $isAdmin
     *
     * @return User
     */
    public function createBasicUser($username, $email = '', $password, $isAdmin = false)
    {
        $user = new User();
        $user
            ->setName('')
            ->setSurname('')
            ->setUsername($username)
//            ->setEmail($email)
            ->setPlainPassword($password)
        ;

        $user->addRole(User::ROLE_DEFAULT);
        if ($isAdmin) {
            $user->addRole('ROLE_ADMIN');
        }

        $this->save($user);

        return $user;
    }

    /**
     * codificatePassword
     *
     * @param User $user
     */
    public function codificatePassword(User $user)
    {
        $encoder = $this->encoderFactory->getEncoder($user);
        $encodedPass = $encoder->encodePassword($user->getPlainPassword(), $user->getSalt());
        $user->setPassword($encodedPass);
    }

    /**
     * setPassword
     *
     * @param User $user
     * @param $password
     *
     * @return User
     */
    public function setPassword(User $user, $password)
    {
        $user->setPlainPassword($password);
        $this->codificatePassword($user);

        return $user;
    }

    /**
     * save
     *
     * @param User $user
     *
     * @return User
     */
    public function save(User $user)
    {
        if (null !== $user->getPlainPassword()) {
            $this->codificatePassword($user);
        }

        $this->om->persist($user);
        $this->om->flush();

        return $user;
    }

    /**
     * login
     *
     * @param User $user
     */
    public function login(User $user)
    {
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->tokenStorage->setToken($token);
    }

    /**
     * generateRandomPassword
     *
     * @param $length
     * @param $includeSymbols
     *
     * @return User
     */
    public function generateRandomPlainPassword($length = 9, $includeSymbols = true)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        if ($includeSymbols) {
            $chars .= '!@#$%^&*()_-=+;:,.?';
        }

        return substr(str_shuffle( $chars ), 0, $length);
    }
}
