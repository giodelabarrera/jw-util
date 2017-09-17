<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RoleType
 * @package AppBundle\Form\Type
 */
class RoleType extends AbstractType
{
    /**
     * @var
     */
    private $roleHierarchy;

    /**
     * RoleType constructor.
     * @param $roleHierarchy
     */
    public function __construct($roleHierarchy)
    {
        $this->roleHierarchy = $roleHierarchy;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => $this->getChoices(),
            'choices_as_values' => true,
            'expanded' => true,
            'multiple' => true,
        ]);
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return ChoiceType::class;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'role';
    }

    /**
     * @return array
     */
    private function getChoices()
    {
        $roles = [];

        array_walk_recursive($this->roleHierarchy, function($val) use (&$roles) {
            $tempValue = str_replace("ROLE_", '', $val);
            $tempValue = ucwords(strtolower(str_replace("_", ' ', $tempValue)));
            $roles[$tempValue] = $val;
        });

        return array_unique($roles);
    }
}
