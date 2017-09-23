<?php

namespace AppBundle\Form;

use AppBundle\Form\Type\DateTimePickerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrivilegioType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nombre')
            ->add('slug', TextType::class, array(
                'required' => false,
                'disabled' => true,
            ))
            ->add('created', DateTimePickerType::class, [
                'disabled' => true,
                'required' => false,
            ])
            ->add('updated', DateTimePickerType::class, [
                'disabled' => true,
                'required' => false,
            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Privilegio'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_privilegio';
    }


}
