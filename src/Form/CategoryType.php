<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $inputClasses = 'w-full bg-[#110c09] border border-[#3b271c] rounded-xl px-4 py-2.5 text-sm text-white placeholder-stone-600 focus:outline-none focus:border-[#8CD62B] transition';

        $builder
            ->add('name', TextType::class, [
                'label' => 'NOM DE LA CATÉGORIE',
                'attr' => [
                    'class' => $inputClasses,
                    'placeholder' => 'Ex: Entrées Gourmet, Desserts, Boissons...'
                ]
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'DESCRIPTION (OPTIONNELLE)',
                'attr' => [
                    'class' => $inputClasses . ' h-24 resize-none',
                    'placeholder' => 'Une courte présentation accrocheuse de cette sélection...'
                ]
            ])
            ->add('icon', TextType::class, [
                'required' => false,
                'label' => 'ICÔNE OU EMOJI',
                'attr' => [
                    'class' => $inputClasses,
                    'placeholder' => 'Ex: 🥗, 🥩, 🍷, 🍰'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}