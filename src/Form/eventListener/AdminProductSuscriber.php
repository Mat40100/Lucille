<?php


namespace App\Form\eventListener;


use App\Form\FileType;
use App\Form\LivrableType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AdminProductSuscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [FormEvents::PRE_SET_DATA => 'preSetData'];
    }

    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();

        $form->add('isOffLinePayed', ChoiceType::class, [
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
        ->add('price', IntegerType::class, [
            'label' => 'Prix de la commande',
            'required' => false
        ]);
    }
}