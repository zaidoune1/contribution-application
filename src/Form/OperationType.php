<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\OperationsBancaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\Request;

class OperationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

        ->add('dateOperation', DateType::class, [
            'attr' => [
            'class' => 'mt-2',
            'placeholder' => "date_operation"
        ],
            'label' => 'Date_operation',
            'label_attr' => ['class' => 'form-label mt-4'],
        ])

            ->add('libelle', TextType::class, [
                'attr' => [
                'class' => 'form-control mt-2',
                'placeholder' => "exemple CB BK0012"
            ],
                'label' => 'Libelle',
                'label_attr' => ['class' => 'form-label mt-3'],
            ])

            ->add('longitude', NumberType::class, [
                'attr' => [
                'class' => 'form-control mt-2',
                'placeholder' => "longitude exemple : 48.87678"
            ],
                'label' => 'Position Gps : longitude',
                'label_attr' => ['class' => 'form-label mt-3'],
            ])

            ->add('latitude', NumberType::class, [
                'attr' => [
                'class' => 'form-control mt-2 ',
                'placeholder' => "latitude exemple : 57.86443"
            ],
                'label' => 'Position Gps : latitude',
                'label_attr' => ['class' => 'form-label mt-3'],
        ]);

            $builder->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-5',
                    'label_attr' => "Valider"
                ]
            ]);
        }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OperationsBancaire::class,
        ]);
    }
}

