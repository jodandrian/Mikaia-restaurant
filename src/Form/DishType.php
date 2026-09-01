<?php

namespace App\Form;

use App\Entity\Dish;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DishType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Classe Tailwind réutilisable pour tous les champs du formulaire
        $inputClasses = 'w-full bg-[#110c09] border border-[#3b271c] rounded-xl px-4 py-2.5 text-sm text-white placeholder-stone-600 focus:outline-none focus:border-[#8CD62B] transition';

        $builder
            ->add('name', TextType::class, [
                'label' => 'Dish Title',
                'attr' => [
                    'class' => $inputClasses,
                    'placeholder' => 'Ex: Le Burger Signature Mikaia'
                ]
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name', // ou 'nomCategory' selon votre entité
                'label' => 'Category',
                'placeholder' => '-- Select a Category --',
                'attr' => ['class' => $inputClasses]
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Price (Ariary)',
                'currency' => '',
                'attr' => [
                    'class' => $inputClasses,
                    'placeholder' => 'Ex: 25000'
                ]
            ])
            ->add('badge', TextType::class, [
                'label' => 'Promotional Badge',
                'required' => false,
                'attr' => [
                    'class' => $inputClasses,
                    'placeholder' => 'Ex: Chef Special, Seasonal'
                ]
            ])
            ->add('imageUrl', TextType::class, [
                'label' => 'Image URL',
                'required' => false,
                'attr' => [
                    'class' => $inputClasses,
                    'placeholder' => 'https://images.unsplash.com/...'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Detailed Description',
                'attr' => [
                    'class' => $inputClasses . ' h-24 resize-none',
                    'placeholder' => 'Ingredients, cooking methods...'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dish::class,
        ]);
    }
}