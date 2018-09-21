<?php

namespace DH\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;

class PasswordResetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'options' => ['translation_domain' => 'UserBundle'],
                'first_options' => [
                    'label' => 'password.reset.new_password',
                    'attr' => ['placeholder' => 'password.reset.new_password']
                ],
                'second_options' => [
                    'label' => 'password.reset.new_password_confirmation',
                    'attr' => ['placeholder' => 'password.reset.new_password_confirmation']
                ],
                'invalid_message' => 'password.reset.password.mismatch',
            ])
        ;
    }
}
