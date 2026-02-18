<?php

namespace App\Form;

use App\Entity\Pet;
use App\Entity\Post;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use App\Enum\PostVisibility;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'class' => 'w-full border-none focus:ring-0 text-xl bg-transparent text-(--text-dark-gray) placeholder-gray-400 resize-none min-h-10 max-h-40 p-0 focus-visible:outline-none',
                    'style' => 'field-sizing: content;',
                    'placeholder' => "What's new?",
                    'id' => 'new-post-textarea',
                ],
            ])
            ->add('visibility', EnumType::class, [
                'class' => PostVisibility::class,
                'expanded' => false,
                'data' => PostVisibility::PUBLIC ,
                'choice_label' => fn(PostVisibility $choice) => match ($choice) {
                    PostVisibility::PUBLIC => '🌍 Public',
                    PostVisibility::FOLLOWERS => '👥 Followers',
                    PostVisibility::PRIVATE => '🔒 Private',
                },
                'attr' => [
                    'class' => 'appearance-none bg-(--dark-white) block w-full px-1 py-1 text-xs text-(--dark-gray)/70 rounded-sm cursor-pointer focus:outline-none focus:border-primary/50 transition-colors uppercase tracking-wider',
                ],
                'choice_attr' => fn() => ['class' => 'bg-(--green-100) text-(--dark-gray)'],
            ])
            ->add('imageFiles', FileType::class, [
                'label' => false,
                'mapped' => false, 
                'multiple' => true, 
                'required' => false,
                'attr' => [
                    'class' => 'hidden',
                    'accept' => 'image/*',
                    'data-post-images-uploader-preview-target' => 'input',
                    'data-action'=>'change->post-images-uploader-preview#update'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
