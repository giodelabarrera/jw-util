<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateTimePickerType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'widget' => 'single_text',
            'with_seconds'=> true,
            'format' => 'YYYY-MM-dd HH:mm:ss',
            'attr' => [
//                'format' => 'DD-MM-YYYY HH:mm:ss',
                'format' => 'YYYY-MM-DD HH:mm:ss',
                'locale' => 'en'
            ]
        ]);
    }

    public function getParent()
    {
        return DateTimeType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'datetimepicker';
    }
}
