<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Category; // Import de la catégorie
use Symfony\Bridge\Doctrine\Form\Type\EntityType; // Import pour le menu déroulant
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'label' => 'Titre de l\'article'
            ])
            ->add('content', null, [
                'label' => 'Contenu'
            ])
            ->add('picture', null, [
                'label' => 'Lien de l\'image (URL)',
                'required' => false,
                'attr' => [
                    'placeholder' => 'https://example.com/image.jpg (facultatif)'
                ]
            ])
            // On ajoute le choix de la catégorie (très important pour le blog !)
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name', // On affiche le nom de la catégorie
                'label' => 'Catégorie'
            ])
        ;
        
        // REMARQUE : 'publishedAt' et 'author' ont été retirés 
        // car ils sont gérés automatiquement dans PostController.
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}