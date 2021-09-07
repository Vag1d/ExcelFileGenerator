<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, array(
                'label' => 'Имя пользователя'
            ))
            ->add('fio', TextType::class, array(
                'label' => 'ФИО'
            ))
            ->add('password', PasswordType::class, [
                'required' => $options['is_creating'],
                'help' => "Оставьте поле пустым, чтобы его не менять",
                'label' => "Пароль",
                'empty_data' => ""
            ]);

        $builder->add('roles', ChoiceType::class, [
            'multiple' => false,
            'expanded' => true,
            'choices' => array_flip($options['roles']),

            'label' => 'Привилегии и ограничения',
            'help' => 'Привилегии вступают в силу сразу после сохранения данных.',
            'label_attr' => [
                'class' => 'radio-custom'
            ],
        ])
            ->add('save', SubmitType::class, array(
                'label' => 'Сохранить'
            ));
        $builder->get('roles')->addModelTransformer(new CallbackTransformer(
            function ($rolesAsArray) {
                return $rolesAsArray[0] ?? "";
            },
            function ($rolesAsString) {
                return [$rolesAsString ?? ""];
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'roles' => [],
            'is_creating' => false,
        ]);
    }
}
