<?php

namespace App\Form;

use App\Entity\Bundesland;
use App\Entity\Nationen;
use App\Entity\ZFKenner;
use App\Entity\Zielfahndung;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ZielfahndungType extends AbstractType
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
                'required' => false,
                'label' => 'Vorg.Nr',
                'empty_data' => 'Unbekannt', // Standardwert setzen
            ])

            ->add('sachbearbeiter1', TextType::class, [
                'required' => false,
                'label' => 'Sachbearbeiter 1'
            ])
            ->add('sachbearbeiter2', TextType::class, [
                'required' => false,
                'label' => 'Sachbearbeiter 2'
            ])
            ->add('festnahme', DateType::class, [
                'required' => false,
                'label' => 'Festnahme / Rückkehr'
            ])
            ->add('einsendendeDst', TextType::class, [
                'required' => false,
                'label' => 'Einsendende Dnst.'
                ])
            ->add('sachbearbeitendeDst', TextType::class, [
                'required' => false,
                'label' => 'Sachbearbeitende Dnst.'
            ])
            ->add('bundesland', EntityType::class, options: [
                'class' => Bundesland::class,
                'choice_label' => 'bezeichnung',
                'required' => false,
                'placeholder' => 'Bundesland',
                'label' => '',
                'attr' => ['class' => 'select']
            ])
            ->add('eingang', TextType::class, [
                'required' => false,
                'label' => 'Eingang'
            ])
            ->add('geschaeftszeichen', TextType::class, [
                'required' => false,
                'label' => 'Geschäftszeichen'
            ])
            ->add('zfkenner', EntityType::class, [
                'class' => ZFKenner::class,
                'choice_label' => 'bezeichnung',
                'required' => false,
                'placeholder' => 'ZF-Kenner',
                'label' => '',
                'attr' => ['class' => 'select']
            ])
            ->add('auslaendische_behoerde', EntityType::class, options: [
                'class' => Nationen::class,
                'choice_label' => 'bezeichnung',
                'required' => false,
                'placeholder' => 'Ausländische Behörde',
                'label' => '',
                'attr' => ['class' => 'select']
            ])
            ->add('erfassung', DateType::class, [
                'required' => false,
                'label' => 'Erfassung'
            ])
            ->add('aussonderung', DateType::class, [
                'required' => false,
                'label' => 'Aussonderung',
            ])
            ->add('inhalt', TextareaType::class, [
                'required' => false,
                'label' => 'Inhalt des Schreibens',
            ])
            ->add('massnahmen', TextareaType::class, [
                'required' => false,
                'label' => 'Massnahmen',
            ])
            ->add('bemerkung', TextareaType::class, [
                'required' => false,
                'label' => 'Bemerkung',
            ])
            ->add('wv', DateType::class, [
                'required' => false,
                'label' => 'WV-Datum'
            ])
            ->add('erledigt', DateType::class, [
                'required' => false,
                'label' => 'Erledigt'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Zielfahndung::class,
            'method' => 'POST',
        ]);
    }
}