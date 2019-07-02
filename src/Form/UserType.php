<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($options['email'] === true) {
            $builder
                ->add('email', EmailType::class, [
                    'attr' => [
                        'placeholder' => 'Email'
                    ]
                ])
            ;
        }
        elseif($options['reset'] === true) {
            $builder
                ->add('password',  RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'The password fields must match.',
                    'options' => ['attr' => ['class' => 'password-field']],
                    'required' => true,
                    'first_options'  => ['label' => 'Password', 'attr' => [
                        'placeholder' => 'Mot de passe'
                    ]],
                    'second_options' => ['label' => 'Repeat Password', 'attr' => [
                        'placeholder' => 'Mot de passe'
                    ]],
                ])
            ;
        }
        else {
            $builder
                ->add('email', EmailType::class, [
                    'attr' => [
                        'placeholder' => 'E-mail'
                    ]
                ])
                ->add('password',  RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'The password fields must match.',
                    'options' => ['attr' => ['class' => 'password-field']],
                    'required' => true,
                    'first_options'  => ['label' => 'Password'],
                    'second_options' => ['label' => 'Repeat Password'],
                ])
                ->add('lastName', TextType::class, [
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'Nom de famille'
                    ]
                ])
                ->add('firstName', TextType::class, [
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'Prénom'
                    ]
                ])
                ->add('company', TextType::class, [
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'Entreprise'
                    ]
                ])
                ->add('billingAddress', TextareaType::class, [
                    'attr' => [
                    'placeholder' => 'Adresse de facturation'
                    ]
                ])
                ->add('phoneNumber', TelType::class, [
                    'attr' => [
                        'placeholder' => 'Numéro de téléphone'
                    ]
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'email' => false,
            'reset' => false
        ]);
    }
}
