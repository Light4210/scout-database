<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['required' => true, 'attr' => ['maxlength' => 50, 'minlength' => 2, 'placeholder' => 'Name']])
            ->add('surname', TextType::class, ['required' => true, 'attr' => ['maxlength' => 50, 'minlength' => 2, 'placeholder' => 'Surname']])
            ->add('middleName', TextType::class, ['required' => false, 'attr' => ['maxlength' => 50, 'minlength' => 2, 'placeholder' => 'Middle name']])
            ->add('dateOfBirth', DateType::class, ['required' => false,])
            ->add('address', TextType::class, ['required' => false, 'attr' => ['maxlength' => 95, 'minlength' => 2, 'placeholder' => 'address']])
            ->add('phone_number', TextType::class, ['required' => false, 'attr' => ['maxlength' => 15, 'minlength' => 9, 'placeholder' => 'phone number']])
            ->add('photo', FileType::class, [
                'data_class' => null,
                'required' => false
            ])
            ->add('dealScan', FileType::class, [
                'data_class' => null,
                'required' => false
            ])
            ->add('Change', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
