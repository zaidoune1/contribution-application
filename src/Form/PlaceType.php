<?php

namespace App\Form;

use App\Controller\PlaceController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use App\Entity\OperationsBancaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\Request;


class PlaceType extends AbstractType
{ 
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Récupérer les données de l'api passées en option
        $places = $options['places'];

        // Créer un tableau associatif avec les valeurs et les libellés des choix
        $choices = [];
        foreach ($places as $place) {
            $choices[$place['name']] = $place['name'];
        }

        $builder->add('places', ChoiceType::class, [
            // Passer le tableau associatif en option choices
            'choices' => $choices,
            'placeholder' => 'Choisir un lieu',
            'attr' => [
                'class' => 'form-select',
            ],

        ]);
        $builder
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-5',
                ],
                'label' => "Valider"

            ])
            ->add('Annuler', ButtonType::class, [
                'label' => 'Passer à mes contribution',
                'attr' => [
                    'class' => 'btn btn-info mt-4',
                    'onclick' => 'window.location.href = "/contribution"',
                ],
            ]);
        }

        // Définir la méthode configureOptions pour passer les données de l'api en option
        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults([
                // Passer un tableau vide par défaut
                'places' => [],
            ]);
        }

}