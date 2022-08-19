<?php

namespace App\Form;

use App\Entity\Group;
use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddTrickType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('group', EntityType::class, array(
                'label' => 'Action',
                'required' => true,
                'class' => Group::class,
                'choices' => $this->entityManager->getRepository(Group::class)->findAll(),
                'choice_label' => 'name',
            ))
            ->add('gallery', CollectionType::class, array(
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'entry_type' => MediaType::class,
                'attr' => [
                    'class' => "add_trick_gallery"
                ]
            ))

            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
