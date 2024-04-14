<?php

namespace App\Form;

use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as SFType;
use Symfony\Component\Validator\Constraints\GreaterThan;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebut')
            ->add('nbrHeure', SFType\IntegerType::class, [
                'required' => true,
                'constraints' => [
                    new GreaterThan(['value' => 0, 'message' => 'Le nombre d\'heures doit être supérieur à zéro.']),
                ],
            ])
            ->add('departement', SFType\TextType::class, ['required' => true])
            ->add('produit', EntityType::class, [
                'class' => 'App\Entity\Produit',
                'choice_label' => 'libelle',
                'required' => true,
            ])
            ->add('save', SFType\SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
