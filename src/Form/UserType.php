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
                    'invalid_message' => 'Les mots de passe doivent être identiques.',
                    'options' => ['attr' => ['class' => 'password-field']],
                    'required' => true,
                    'first_options'  => ['label' => 'Mot de passe', 'attr' => [
                        'placeholder' => 'Mot de passe'
                    ]],
                    'second_options' => ['label' => 'Répétez votre mot de passe', 'attr' => [
                        'placeholder' => 'Mot de passe'
                    ]],
                ])
            ;
        }
        else {
            $builder
                ->add('email', EmailType::class, [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'E-mail'
                    ]
                ])
                ->add('password',  RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les mots de passe doivent être identiques.',
                    'options' => ['attr' => ['class' => 'password-field']],
                    'required' => true,
                    'first_options'  => ['label' => 'Mot de passe', 'attr' => [
                        'placeholder' => 'Mot de passe'
                    ]],
                    'second_options' => ['label' => 'Répétez votre mot de passe', 'attr' => [
                        'placeholder' => 'Mot de passe'
                    ]],
                ])
                ->add('lastName', TextType::class, [
                    'label' => false,
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'Nom de famille (ou le nom de l\'entreprise)'
                    ]
                ])
                ->add('firstName', TextType::class, [
                    'label' => false,
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'Prénom (ou le nom de l\'entreprise)'
                    ]
                ])
                ->add('company', TextType::class, [
                    'label' => false,
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'Entreprise (ou nom / prénom)'
                    ]
                ])
                ->add('billingAddress', TextareaType::class, [
                    'label' => false,
                    'attr' => [
                    'placeholder' => 'Adresse de facturation'
                    ]
                ])
                ->add('phoneNumber', TelType::class, [
                    'label' => false,
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
