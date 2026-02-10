<?php

namespace App\Form;

use App\Entity\Pet;
use App\Entity\User;
use App\Enum\PetGender;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\PetType as PetTypeEntity;
use App\Entity\PetSubtype;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints as Assert;


class PetType extends AbstractType
{
    public $selectorClasses = 'form-select-custom cursor-pointer w-full pl-12 pr-10 py-3.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-(--green-300)/20 focus:border-(--green-300) transition-all outline-none appearance-none';
    public $textClasses = 'w-full pl-12 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-(--green-300)/20 focus:border-(--green-300) transition-all outline-none placeholder:text-gray-400';
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Pet Name',
                'attr' => [
                    'class' => $this->textClasses
                ],
                'constraints' => [
                    new NotBlank(
                        message: 'Please enter name.'
                    ),
                    new Length(
                        max: 255,
                        maxMessage: 'Name cannot be longer than {{ limit }} characters.'
                    )
                ],
            ])
            ->add('gender', EnumType::class, [
                'class' => PetGender::class,
                'expanded' => true,
                'multiple' => false,
                'data' => PetGender::MALE,
            ])->add('type', EntityType::class, [
                    'class' => PetTypeEntity::class,
                    'choice_label' => 'name',
                    'placeholder' => 'Select*',
                    'attr' => [
                        'class' => $this->selectorClasses,
                        'data-pet-type-target' => 'type',
                        'data-action' => 'change->pet-type#onTypeChange',
                    ],
                ])
            ->add('subtype', EntityType::class, [
                'class' => PetSubtype::class,
                'choice_label' => 'name',
                'placeholder' => 'Select*',
                'attr' => [
                    'class' => $this->selectorClasses,
                    'data-pet-type-target' => 'subtype',
                ],
            ])
            ->add('profilePicture', FileType::class, [
                'label' => 'Profile Picture',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Assert\File(
                        maxSize: '1024k',
                        extensions: ['png', 'jpg', 'jpeg', 'webp'],
                        extensionsMessage: 'Please upload a valid picture!',
                    )
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pet::class,
        ]);
    }
}
