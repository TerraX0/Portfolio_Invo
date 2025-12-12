<?php

namespace App\Form;

use App\Entity\IntZusammenarbeit;
use App\Entity\IPKenner;
use App\Entity\Nationen;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IntzusammenarbeitType extends AbstractType
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
            ->add('vorgangsdatum', DateType::class, [
                'required' => false,
                'label' => 'Vorgangsdatum',
            ])
            ->add('einsendende_dst', TextType::class, [
                'required' => false,
                'label' => 'Einsendende Dst.',
            ])
            ->add('eingang', TextType::class, [
                'required' => false,
                'label' => 'Eingang',
            ])
            ->add('geschaeftszeichen', TextType::class, [
                'required' => false,
                'label' => 'Geschäftszeichen',
            ])
            ->add('ipkenner', EntityType::class, [
                'class' => IPKenner::class,
                'choice_label' => 'bezeichnung',
                'required' => false,
                'label' => '',
                'placeholder' => 'IP Kenner',
                'attr' => ['class' => 'select'],
            ])
            ->add('auslaendische_behoerde', EntityType::class, [
                'class' => Nationen::class,
                'choice_label' => 'bezeichnung',
                'required' => false,
                'label' => '',
                'placeholder' => 'Ausländische Behörde',
                'attr' => ['class' => 'select'],
            ])
            ->add('erfassung', DateType::class, [
                'required' => false,
                'label' => 'Erfassung',
            ])
            ->add('aussonderung', DateType::class, [
                'required' => false,
                'label' => 'Aussonderung',
            ])
            ->add('name', TextType::class, [
                'required' => false,
                'label' => 'Name',
            ])
            ->add('vorname', TextType::class, [
                'required' => false,
                'label' => 'Vorname',
            ])
            ->add('gebdatum', DateType::class, [
                'required' => false,
                'label' => 'Geburtsdatum',
            ])
            ->add('inhalt', TextareaType::class, [
                'required' => false,
                'label' => 'Inhalt',
            ])
            ->add('link', TextareaType::class, [
                'required' => false,
                'label' => 'Link',
            ])
            ->add('massnahmen', TextareaType::class, [
                'required' => false,
                'label' => 'Massnahmen',
            ])
            ->add('bearbeitung', TextareaType::class, [
                'required' => false,
                'label' => 'Bearbeitung',
            ])
            ->add('hinweise', TextareaType::class, [
                'required' => false,
                'label' => 'Hinweise',
            ])
            ->add('wv', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'WV-Datum',
            ])
            ->add('erledigung', DateType::class, [
                'required' => false,
                'label' => 'Erledigung',
            ])
            ->add('reminder', TextType::class, [
                'required' => false,
                'label' => 'Reminder',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => IntZusammenarbeit::class,
            'method' => 'POST',
        ]);
    }
}
