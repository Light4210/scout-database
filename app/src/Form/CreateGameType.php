<?php

namespace App\Form;

use App\Entity\Game;
use App\Entity\Invite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;

class CreateGameType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['required' => true, 'attr' => ['maxlength' => 100, 'minlength' => 4, 'placeholder' => 'Назва']])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    ''=>'',
                    'Велика гра' => Game::BIG_GAME,
                    'Звичайна гра' => Game::SIMPLE_GAME
                ],
                'required' => true])
            ->add('min_users', NumberType::class, ['required' => true, 'attr' => ['placeholder' => 'мін','maxlength' => 2, 'minlength' => 1,]])
            ->add('time', NumberType::class, ['required' => false, 'attr' => ['placeholder' => 'час на 1 раунд']])
            ->add('description', FroalaEditorType::class, ['label' => 'опишіть гру', 'required' => true, 'attr' => ['class' => 'editor', 'minlength' => 20, 'placeholder' => 'Опис гри']])

            ->add('publish', SubmitType::class, ['label' => 'Надіслати']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}
