<?php

namespace DH\UserBundle\Security;

use DH\UserBundle\Exception\AccountDeletedException;
use DH\UserBundle\Exception\AccountLockedException;
use DH\UserBundle\Exception\PasswordResetRequiredException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @see https://symfony.com/doc/current/security/user_checkers.html
 */
class UserChecker implements UserCheckerInterface
{
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function checkPreAuth(UserInterface $user): void
    {
        // user is deleted, show a generic Account Not Found message.
        if ($user->isDeleted()) {
            throw new AccountDeletedException();
        }

        // user account is locked, the user may be notified
        if ($user->isLocked()) {
            throw new AccountLockedException();
        }

        // user account is expired, the user may be notified
        if ($user->isExpired()) {
            throw new AccountExpiredException();
        }

        // password reset is required
        if ($user->isPasswordResetRequired()) {
            // generate then store a reset token in the user entity
            $token = TokenGenerator::generateToken();
            $user->setResetToken($token);
            $this->registry->getManager()->persist($user);
            $this->registry->getManager()->flush();

            // store the reset token in the exception
            $exception = new PasswordResetRequiredException();
            $exception->setResetToken($token);

            throw $exception;
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
    }
}
