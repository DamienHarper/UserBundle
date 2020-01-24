<?php

namespace DH\UserBundle\Exception;

use Symfony\Component\Security\Core\Exception\AccountStatusException;

class PasswordResetRequiredException extends AccountStatusException
{
    /**
     * @var string
     */
    private $resetToken;

    /**
     * Get the value of resetToken.
     */
    public function getResetToken(): string
    {
        return $this->resetToken;
    }

    /**
     * Set the value of resetToken.
     */
    public function setResetToken(string $resetToken): void
    {
        $this->resetToken = $resetToken;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageKey()
    {
        return 'login.exception.password_reset_required';
    }

    /**
     * {@inheritdoc}
     */
    public function __serialize(): array
    {
        return [$this->resetToken, parent::__serialize()];
    }

    /**
     * {@inheritdoc}
     */
    public function __unserialize(array $data): void
    {
        [$this->resetToken, $parentData] = $data;
        parent::__unserialize($parentData);
    }
}
