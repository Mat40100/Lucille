<?php

namespace App\Form;

use App\Entity\Product;
use App\Form\eventListener\addFileFieldSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
                'label' => false,
                'attr' => [
                    'placeholder' => 'Commentaires'
                ]
            ])
        ;

        $builder->addEventSubscriber(new addFileFieldSubscriber());

        if ($this->security->isGranted("ROLE_ADMIN")) {
            $builder
                ->add('isPayed', ChoiceType::class, [
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
                        'En attente' => 'En attente'
                    ],
                    'label' => 'Etat de la commande'
                ]);

             if (!$options['data']->getIsValid()) {
                 $builder
                     ->add('price', IntegerType::class, [
                     'label' => 'Prix de la commande',
                     'required' => false
                     ])
                     ->add('isValid', ChoiceType::class, [
                         'choices'  => [
                             'Oui' => true,
                             'Non' => false,
                         ],
                         'label' => 'Validée ?'
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
