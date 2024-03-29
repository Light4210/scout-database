<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserEditType extends AbstractType
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
            ->add('phoneNumber', TextType::class, ['required' => false, 'attr' => ['maxlength' => 15, 'minlength' => 9, 'placeholder' => 'phone number']])
            ->add('photo', FileType::class, [
                'data_class' => null,
                'required' => false
            ])
            ->add('dealScan', FileType::class, [
                'data_class' => null,
                'required' => false
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    User::STATUS_PASSIVE => User::STATUS_PASSIVE,
                    User::STATUS_ACTIVE => User::STATUS_ACTIVE
                ],
                'required' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Змінити'
            ]);

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            // Retrieve submitted data
            $form = $event->getForm();
            $user = $form->getData();

            // Test if upload image is null (maybe adapt it to work with your code)
            if ($form->get('photo')->getData() !== null) {
                $user->setPhoto($form->get('photo')->getData());
            }

            if ($form->get('dealScan')->getData()  !== null) {
                $user->setDealScan($form->get('dealScan')->getData());
            }

            $event->setData($user);
        });
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
