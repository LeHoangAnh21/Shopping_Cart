<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Author;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,
            [
                'label' => 'Book Title',
                'required' => true
            ])
            ->add('publisher', TextType::class,
            [
                'label' => 'Publisher Name',
                'required' => true
            ])
            ->add('price', MoneyType::class,
            [
                'label' => 'Book Price',
                'required' => true,
                'currency' => "USD"
            ])
            ->add('quantity', IntegerType::class,
            [
                'label' => 'Book Quantity',
                'required' => true,
            ])
            ->add('image', FileType::class,
            [
                'label' => 'Book Image',
                'data_class' => null,
                'required' => is_null($builder->getData()->getImage())
            ])
            ->add('author', EntityType::class,
            [
                'label' => 'Author',
                'class' => Author::class, 
                'choice_label' => "name",
                'multiple' => true,
                'expanded' => false
            ])
            ->add('category', EntityType::class,
                [
                    'label' => 'Category',
                    'class' => Category::class, 
                    'choice_label' => "name",
                    'multiple' => true,
                    'expanded' => false
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
