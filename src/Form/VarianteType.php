<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Variante;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VarianteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('prix')
            //->add('prix', NumberType::class)
            //->add('prix', NumberType::class, ["label" => "Prix en Euros", "html5" => true, "attr" => ["min" => "1" , "max" => "9999.99", "step" => ".5"]])
            ->add('prix', MoneyType::class, [
                'currency' => 'EUR'
            ])
            ->add('memoire')
            ->add('core')
            ->add('espaceDisque')
            ->add('couleur')
            ->add('qteStoque')
            ->add('master', ChoiceType::class, [
                "label" => "Master",
                'choices'  => [
                    'Non' => 0,
                    'Oui' => 1,
                ],
                //'choice_value' => 'Non'
            ])
            ->add('remise', IntegerType::class, ["label" => "Remise en %", "attr" => ["min" => "0" , "max" => "90"]])
            //->add('produit', EntityType::class, [
            //    'class' =>Produit::class,
            //    'choice_label' => 'id'
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Variante::class,
        ]);
    }
}
