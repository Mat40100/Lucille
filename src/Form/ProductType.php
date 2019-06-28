<?php

namespace App\Form;

use App\Entity\Product;
use App\Form\eventListener\addFileFieldSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('comment', TextareaType::class)
        ;

        $builder->addEventSubscriber(new addFileFieldSubscriber());

        if ($this->security->isGranted("ROLE_ADMIN")) {
            $builder
                ->add('isValid', ChoiceType::class, [
                    'choices'  => [
                        'Yes' => true,
                        'No' => false,
                    ],
                ])
                ->add('isPayed', ChoiceType::class, [
                    'choices'  => [
                        'Yes' => true,
                        'No' => false,
                    ],
                ])
                ->add('state', ChoiceType::class, [
                    'choices'  => [
                        'Started' => 'started',
                        'Over' => 'over',
                        'Pending' => 'pending'
                    ],
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
