<?php

namespace App\Form;

use App\Controller\PlaceController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\OperationsBancaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\Request;


class PlaceupdateType extends AbstractType
{ 
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $places = $options['places'];
        
        $choices = [];
        foreach ($places as $place) {
            $choices[$place['name']] = $place['name'];
        }

        $builder->add('places', ChoiceType::class, [
            'choices' => $choices,
            'attr' => [
                'class' => 'form-select form-select-lg'
            ]
        ]);
        $builder->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary btn-lg',
                    'label_attr' => "Valider"
                ]
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