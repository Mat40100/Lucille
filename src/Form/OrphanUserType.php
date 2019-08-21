<?php

namespace App\Form;

use App\Entity\OrphanUser;
use App\Form\eventListener\validatedProductSuscriber;
use App\Form\eventListener\removeProductFieldSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class OrphanUserType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName', TextType::class, [
                'empty_data' => '',
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom de famille'
                ]
            ])
            ->add('firstName', TextType::class, [
                'empty_data' => '',
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prénom'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'E-mail'
                ]
            ])
            ->add('postalAddress', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Adresse postale / Adresse de facturation'
                ]
            ])
            ->add('phoneNumber', TelType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Téléphone'
                ]
            ])
            ->add('company', TextType::class, [
                'empty_data' => '',
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entreprise'
                ]
            ])
        ;

        if (null === $options['data']->getId()) {
            $builder->add('product', ProductType::class);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrphanUser::class,
        ]);
    }
}
