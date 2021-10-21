<?php

namespace App\Form;

use App\Entity\Struct;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StructCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type')
            ->add('name')
            ->add('city')
            ->add('adress')
            ->add('latitude')
            ->add('longitude')
            ->add('created_at')
            ->add('updated_at')
            ->add('sheaf')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Struct::class,
        ]);
    }
}
