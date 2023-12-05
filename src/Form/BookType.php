<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Author;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ref', null, [
                'label' => 'Reference',
            ])
            ->add('title', null, [
                'label' => 'Title',
            ])
            ->add('category', null, [
                'label' => 'Category',
            ])
            ->add('publicationDate', null, [
                'label' => 'Publication Date',
            ])
            ->add('published', null, [
                'label' => 'Published',
            ])
            ->add('author', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'username', // Display author usernames in the dropdown
                'label' => 'Author',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}

