<?php

namespace App\Form;

use App\Entity\IntZusammenarbeit;
use App\Entity\IPKenner;
use App\Entity\Nationen;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

//TODO: Logik für Operatoren "von-bis" ist noch nicht implementiert
class IntzusammenarbeitSearchType extends AbstractType
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
        $this->addDateFilter($builder, 'vorgangsdatum');
        $this->addDateFilter($builder, 'gebdatum');
        $this->addDateFilter($builder, 'wv');
        $this->addDateFilter($builder, 'erledigung');
        $this->addDateFilter($builder, 'aussonderung');
        $this->addDateFilter($builder, 'erfassung');
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
