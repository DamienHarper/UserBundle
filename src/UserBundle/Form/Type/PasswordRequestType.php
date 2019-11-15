<?php

namespace DH\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PasswordRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'translation_domain' => 'UserBundle',
                'label' => 'password.request.username.label',
                'attr' => [
                    'placeholder' => 'password.request.username.placeholder',
                    'autocomplete' => 'off',
                ],
            ])
        ;
    }
}
