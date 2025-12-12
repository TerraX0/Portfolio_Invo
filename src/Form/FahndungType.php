<?php

namespace App\Form;

use App\Entity\Fahndung;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class FahndungType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', TextType::class, [
                'required' => false,
                'label' => 'ID',
                'disabled' => true,
            ])
            ->add('vorgangsnummer', TextType::class, [
                'empty_data' => 'Unbekannt', // Standardwert setzen
                'required' => false,
                'label' => 'Vorg.Nr',
            ])
            ->add('name', TextType::class, [
            'required' => false,
            'label' => 'Familienname',
            ])
            ->add('vorname', TextType::class, [
            'required' => false,
            'label' => 'Vorname',
    ])
            ->add('gebdatum', DateType::class, [
                'required' => false,
                'label' => 'Geburtsdatum',
            ])
            ->add('loeschung', DateType::class, [
                'required' => false,
                'label' => 'Löschung',
            ])
            ->add('erfassung', DateType::class, [
                'required' => false,
                'label' => 'Erfassung',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fahndung::class,
        ]);
    }
}
