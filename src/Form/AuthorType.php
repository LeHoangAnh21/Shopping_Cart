<?php

namespace App\Form;

use App\Entity\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, 
                [
                    'label' => 'Author Name',
                    'required' => true
                ])
            ->add('birthday', DateType::class, 
                [
                    'label' => 'Author Birthday',
                    'required' => true,
                    'widget' => 'single_text'
                ])
            ->add('nationality', ChoiceType::class,
                [
                    'choices' => 
                    [
                        "Vietnam" => "Vietnam",
                        "Singapore" => "Singapore",
                        "United States" => "United States",
                        "England" => "England",
                        "Germany" => "Germany"
                    ]
                ])
            ->add('image', FileType::class,
                [
                    'label' => "Author Image",
                    'data_class' => null,
                    'required' => is_null($builder->getData()->getImage())
                ])
                // ->add('books', EntityType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Author::class,
        ]);
    }
}
