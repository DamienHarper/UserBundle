<?php

namespace DH\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('_username', TextType::class, [
                'translation_domain' => 'UserBundle',
                'label' => 'login.username.label',
                'attr' => [
                    'placeholder' => 'login.username.placeholder',
                    'autocomplete' => 'off',
                ],
            ])
            ->add('_password', PasswordType::class, [
                'translation_domain' => 'UserBundle',
                'label' => 'login.password.label',
                'attr' => [
                    'placeholder' => 'login.password.placeholder',
                    'autocomplete' => 'off',
                ],
            ])
        ;
    }
}
