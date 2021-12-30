<?php

namespace App\Form;

use App\Entity\Struct;
use App\Entity\User;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class StructCreateType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['required' => true, 'attr' => ['maxlength' => 100, 'minlength' => 2, 'placeholder' => 'Name of struct']])
            ->add('city', TextType::class, ['required' => true, 'attr' => ['maxlength' => 65, 'minlength' => 2, 'placeholder' => 'City in which located struct']])
            ->add('address', TextType::class, ['required' => true, 'attr' => ['maxlength' => 100, 'minlength' => 2, 'placeholder' => 'Adress of residence']])
            ->add('Create', SubmitType::class);;

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $currentUser = $this->security->getUser();
            $struct = $event->getData();
            $struct->setType(User::ACTIVE_MINISTRY[$currentUser->getMinistry()]['struct_slug']);
            $struct->setSheaf($currentUser);
            $struct->setCreatedAt(new \DateTimeImmutable());
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Struct::class,
        ]);
    }
}
