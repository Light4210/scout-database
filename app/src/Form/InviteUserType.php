<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Invite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class InviteUserType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ministry', ChoiceType::class, [
                'choices' => [
                    User::ACTIVE_MINISTRY['sheaf']['name'] => User::ACTIVE_MINISTRY['sheaf']['slug'],
                    User::ACTIVE_MINISTRY['troopLeader']['name'] => User::ACTIVE_MINISTRY['troopLeader']['slug'],
                    User::ACTIVE_MINISTRY['akela']['name'] => User::ACTIVE_MINISTRY['akela']['slug'],
                    User::ACTIVE_MINISTRY['president']['name'] => User::ACTIVE_MINISTRY['president']['slug'],
                    'none' => null
                ],
                'required' => true])
            ->add('email', EmailType::class, ['required' => true, 'attr' => ['maxlength' => 100, 'minlength' => 4, 'placeholder' => 'Email']])
            ->add('Invite', SubmitType::class, ['label' => 'запросити']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Invite::class,
        ]);
    }
}
