<?php

namespace App\Form;

use App\Entity\OrphanUser;
use App\Form\eventListener\addFileFieldSubscriber;
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
        $builder->addEventSubscriber(new removeProductFieldSubscriber());

        $builder
            ->add('lastName', TextType::class)
            ->add('firstName', TextType::class)
            ->add('email', EmailType::class)
            ->add('postalAddress', TextareaType::class)
            ->add('phoneNumber', TelType::class)
            ->add('company', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrphanUser::class,
        ]);
    }
}
