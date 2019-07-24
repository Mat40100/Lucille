<?php

namespace App\Form;

use App\Entity\Product;
use App\Form\eventListener\addFileFieldSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class ProductType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comment', TextareaType::class, [
                'label' => 'Commentaire sur la traduction à produire',
                'attr' => [
                    'placeholder' => 'Commentaires'
                ]
            ])
        ;

        $builder->addEventSubscriber(new addFileFieldSubscriber());

        if ($this->security->isGranted("ROLE_ADMIN")) {
            $builder
                ->add('isOffLinePayed', ChoiceType::class, [
                    'choices'  => [
                        'Oui' => true,
                        'Non' => false,
                    ],
                    'label' => 'Payée ?',
                ])
                ->add('state', ChoiceType::class, [
                    'choices'  => [
                        'Commencée' => 'Commencée',
                        'Terminée' => 'Terminée',
                        'Validée' => 'Validée',
                        'En attente' => 'En attente'
                    ],
                    'label' => 'Etat de la commande'
                ])
                ->add('livrables', CollectionType::class, [
                    'label' => 'Livrables',
                    'required' => false,
                    'by_reference' => false,
                    'entry_type' => LivrableType::class,
                    'entry_options' => ['label' => false],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true
                ])
            ;

            if ($options['data']->getState() == 'En attente' ) {
                $builder
                    ->add('price', IntegerType::class, [
                        'label' => 'Prix de la commande',
                        'required' => false
                    ])
                ;
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class
        ]);
    }
}
