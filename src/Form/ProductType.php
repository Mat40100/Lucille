<?php

namespace App\Form;

use App\Entity\Product;
use App\Form\eventListener\AdminProductSuscriber;
use App\Form\eventListener\ValidatedProductSuscriber;
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
                'label' => 'Commentaire',
                'attr' => [
                    'placeholder' => 'Commentaires'
                ]
            ])
            ->add('files', CollectionType::class, [
                'label' => 'Fichiers à traduire',
                'required' => false,
                'by_reference' => false,
                'entry_type' => FileType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true
            ])
        ;

        if ($this->security->isGranted("ROLE_ADMIN")) {
            $builder->addEventSubscriber(new AdminProductSuscriber());
        }

        /*
         * DELETE FIELDS IF PRODUCT ALREADY VALIDATED
         */
        $builder->addEventSubscriber(new ValidatedProductSuscriber());

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class
        ]);
    }
}
