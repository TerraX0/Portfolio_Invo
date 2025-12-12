<?php

namespace App\Form;

use App\Entity\Fahndung;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class FahndungSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', TextType::class, [
                'required' => false,
                'label' => 'ID',
            ])
            ->add('vorgangsnummer', TextType::class, [
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
        $this->addDateFilter($builder, 'gebdatum');
        $this->addDateFilter($builder, 'erfassung');
        $this->addDateFilter($builder, 'loeschung');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null, // Hier wird das Formular von der Entität entkoppelt
            // 'data_class' => Fahndung::class,
            'method' => 'GET', // GET-Methode für Suchanfragen
            // 'context' => 'create', // Default context is 'create'
        ]);
    }

    private function addDateFilter(FormBuilderInterface $builder, string $fieldName): void
    {
        // Operator
        $builder->add($fieldName . 'Operator', ChoiceType::class, [
            'choices' => [
                'Ist gleich' => 'equals',
                'Größer als' => 'greater_than',
                'Größer oder gleich' => 'greater_than_or_equal',
                'Kleiner als' => 'less_than',
                'Kleiner oder gleich' => 'less_than_or_equal',
                'Von: bis' => 'range',
            ],
            'required' => false,
            'placeholder' => '- Auswahl -'
        ]);

        // Datum
        $builder->add($fieldName, DateType::class, [
            'required' => false,
            'label' => ucfirst($fieldName),
        ]);
    }
}
