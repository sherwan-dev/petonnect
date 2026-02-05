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

class PetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('gender', EnumType::class, [
                'class' => PetGender::class,
                'choice_label' => 'name',
            ])            ->add('type', EntityType::class, [
                'class' => PetTypeEntity::class,
                'choice_label' => 'name', // or whatever field makes sense
                'placeholder' => 'Choose a type',
            ])
            ->add('subtype', EntityType::class, [
                'class' => PetSubtype::class,
                'choice_label' => 'name',
                'placeholder' => 'Choose a subtype',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pet::class,
        ]);
    }
}
