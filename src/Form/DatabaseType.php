<?php

namespace App\Form;

use App\Entity\Database;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DatabaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullNameC30', TextType::class, ['label' => 'Полное наименование СЗО'])
            ->add('shortName', TextType::class, ['label' => 'Сокращенное наименование СЗО'])
            ->add('FIO', TextType::class, ['label' => 'ФИО'])
            ->add('fioGenitive', TextType::class, ['label' => 'ФИО (в род. падеже)'])
            ->add('spot', TextType::class, ['label' => 'Должность'])
            ->add('spot_genitive', TextType::class, ['label' => 'Должность (в род. падеже)'])
            ->add('adres', TextType::class, ['label' => 'Адрес'])
            ->add('numGk', TextType::class, ['label' => 'Номер ГК'])
            ->add('protocol', ChoiceType::class, [
                'label' => 'Протокол',
                'choices' => array_flip(Database::PROTOCOLS),
                'placeholder' => 'Выбрать',
                'attr' => [
                    'class' => 'custom-select',
                    'data-fp-id' => Database::PROTOCOL_FP_ID
                ]
            ])
            ->add('ystaw', TextType::class, ['label' => 'Устав, приказ'])
            ->add('mainFIOFP', TextType::class, ['label' => 'ФИО заведующей ФП', 'required' => false])
            ->add('spotNameFP', TextType::class, ['label' => 'Должность и наименование ФП', 'required' => false])
            ->add('template', ChoiceType::class, [
                'label' => 'Акт оказания услуг',
                'choices' => array_flip(Database::TEMPLATES),
                'placeholder' => 'Выбрать',
                'attr' => [
                    'class' => 'custom-select'
                ],
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Database::class,
        ]);
    }
}
