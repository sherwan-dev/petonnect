<?php

namespace App\Form;

use App\Entity\User;
use App\Enum\Gender;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'attr' => [
                    'placeholder' => 'First name',
                ],
                'required' => true,
                'constraints' => [
                    new NotBlank(
                        message: 'Please enter your first name.'
                    )
                ],
            ])
            ->add('lastName', TextType::class, [
                'attr' => [
                    'placeholder' => 'Last name',
                ],
                'required' => true,
                'constraints' => [
                    new NotBlank(
                        message: 'Please enter your last name.'
                    )
                ],
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Male' => Gender::MALE,
                    'Female' => Gender::FEMALE,
                    'Diverse' => Gender::DIVERSE,
                ],
                'expanded' => true,   // radios instead of <select>
                'multiple' => false,  // one choice only
                'required' => true,
                'label' => 'Gender',
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'Email address',
                ],
                'constraints' => [
                    new NotBlank(
                        message: 'Please enter your email address.'
                    ),
                    new EmailConstraint(
                        message: 'Please enter a valid email address.'
                    ),
                ],
                'required' => true,
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'I accept <a href="/legal">terms and conditions</a>',
                'label_html' => true, 
                'constraints' => [
                    new IsTrue(message: 'You should agree to our terms.'),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder' => 'Password'
                ],
                'constraints' => [
                    new NotBlank(
                        message: 'Please enter a password'
                    ),
                    new Length(
                        min: 6,
                        minMessage: 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        max: 4096,
                    ),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
