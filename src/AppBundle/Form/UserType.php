<?php

namespace AppBundle\Form;

use AppBundle\Entity\Country;
use AppBundle\Entity\User;
use AppBundle\Form\Type\DateTimePickerType;
use AppBundle\Form\Type\RoleType;
use AppBundle\Form\Type\Select2Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UserType
 *
 * @package AppBundle\Form
 */
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $isPasswordRequired = in_array('registration', $options['validation_groups']);

        $builder
            ->add('name')
            ->add('surname')
            ->add('username')
            ->add('email', EmailType::class, [
                'required' => false,
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Password'
                ],
                'second_options' => [
                    'label' => 'Repeat password'
                ],
                'required' => $isPasswordRequired,
            ])
            ->add('roles', RoleType::class, [
                'required' => false
            ])

            ->add('phone')
        ;

//        if ($options['form_action'] == 'edit') {
            $builder
                ->add('created', DateTimePickerType::class, [
                    'label' => 'Creado',
                    'disabled' => true,
                    'required' => false,
                ])
                ->add('updated', DateTimePickerType::class, [
                    'label' => 'Actualizado',
                    'disabled' => true,
                    'required' => false,
                ])
            ;
//        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'        => User::class,
            'validation_groups' => ['Default'],
//            'form_action'       => 'default',
        ]);
    }

    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }
}
