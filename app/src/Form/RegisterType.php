<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Invite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegisterType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['required' => true, 'attr' => ['maxlength' => 50, 'minlength' => 2, 'placeholder' => 'Name']])
            ->add('surname', TextType::class, ['required' => true, 'attr' => ['maxlength' => 50, 'minlength' => 2, 'placeholder' => 'Surname']])
            ->add('middleName', TextType::class, ['required' => false, 'attr' => ['maxlength' => 50, 'minlength' => 2, 'placeholder' => 'Middle name']])
            ->add('dateOfBirth', DateType::class, array(
                'widget' => 'choice',
                'years' => range(date('Y'), date('Y')-100),
            ))
            ->add('address', TextType::class, ['required' => false, 'attr' => ['maxlength' => 95, 'minlength' => 2, 'placeholder' => 'address']])
            ->add('phoneNumber', IntegerType::class, ['required' => true, 'attr' => ['maxlength' => 15, 'minlength' => 9]])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
            ])->add('photo', FileType::class, [
                'data_class' => null,
                'required' => false
            ])
            ->add('dealScan', FileType::class, [
                'data_class' => null,
                'required' => false
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    User::MALE => 'male',
                    User::FEMALE => 'female',
                ],
                'required' => true])
            ->add('submit', SubmitType::class, [
                'label' => 'Зареєструватися'
            ]);
    }

    //status,
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
