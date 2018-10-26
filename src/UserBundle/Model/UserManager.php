<?php

namespace DH\UserBundle\Model;

use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
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

    public function hashPassword(UserInterface $user)
    {
        $plainPassword = $user->getPlainPassword();
        if (0 !== strlen($plainPassword)) {
            $salt = null;
            if (!($this->encoderFactory->getEncoder($user) instanceof BCryptPasswordEncoder)) {
                // salt is not used by bcrypt encoder
                $salt = rtrim(str_replace('+', '.', base64_encode(random_bytes(32))), '=');
            }
            $user->setSalt($salt);

            $hashedPassword = $this->encoderFactory->getEncoder($user)->encodePassword($plainPassword, $user->getSalt());
            $user->setPassword($hashedPassword);
            $user->eraseCredentials();
        }
    }
}