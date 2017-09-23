<?php

namespace AppBundle\Form;

use AppBundle\AppBundle;
use AppBundle\Entity\Privilegio;
use AppBundle\Form\Type\DateTimePickerType;
use AppBundle\Form\Type\Select2Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HermanoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nombre')->add('apellidos')
            ->add('slug', TextType::class, array(
                'required' => false,
                'disabled' => true,
            ))
            ->add('email')->add('telefono')
            ->add('privilegios', Select2Type::class, [
                'class' => Privilegio::class,
                'multiple' => true,
            ])
            ->add('created', DateTimePickerType::class, [
                'disabled' => true,
                'required' => false,
            ])
            ->add('updated', DateTimePickerType::class, [
                'disabled' => true,
                'required' => false,
            ]);;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Hermano'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_hermano';
    }


}
