<?php

namespace DH\UserBundle\Model;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserManager
{
    /**
     * @var PasswordEncoderInterface
     */
    private $encoderFactory;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    public function hashPassword(UserInterface $user): void
    {
        $plainPassword = $user->getPlainPassword();
        if (0 !== mb_strlen($plainPassword)) {
            $hashedPassword = $this->encoderFactory->getEncoder($user)->encodePassword($plainPassword, null);
            $user->setPassword($hashedPassword);
            $user->eraseCredentials();
        }
    }
}
