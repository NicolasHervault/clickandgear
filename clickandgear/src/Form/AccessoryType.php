<?php

namespace App\Form;

use App\Entity\Accessory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccessoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('categorie', ChoiceType::class, [
                'choices' => [
                    'Casque' => 'Casque',
                    'Clavier' => 'clavier',
                    'Souris' => 'Souris',
                    'Tapis de souris' => 'tapis de souris',
                ],
                'label' => 'Catégories'
            ])
            ->add('description')
            ->add('prix')
            ->add('stock')
            ->add('image', FileType::class, [
                'label' => 'Image (Fichier image)',
                'mapped' => false,
                'required' => false,
                'attr' => ['accept' => 'image/*'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Accessory::class,
        ]);
    }
}
