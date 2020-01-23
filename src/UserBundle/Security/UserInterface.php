<?php

namespace DH\UserBundle\Security;

use DateTimeInterface;
use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

interface UserInterface extends BaseUserInterface
{
    /**
     * Get the value of email.
     */
    public function getEmail(): ?string;

    /**
     * Get the value of password_requested_at.
     */
    public function getPasswordRequestedAt(): ?DateTimeInterface;

    /**
     * Set the value of password_requested_at.
     */
    public function setPasswordRequestedAt(?DateTimeInterface $passwordRequestedAt);

    /**
     * Returns plain-text password.
     */
    public function getPlainPassword(): ?string;

    /**
     * Sets plain-text password.
     *
     * @return $this
     */
    public function setPlainPassword(?string $plainPassword);

    /**
     * Get the value of reset_token.
     */
    public function getResetToken(): ?string;

    /**
     * Set the value of reset_token.
     */
    public function setResetToken(?string $token): self;

    /**
     * Return true if user account is locked.
     */
    public function isLocked(): bool;

    /**
     * Return true if user account is deleted.
     */
    public function isDeleted(): bool;

    /**
     * Return true if user account is expired.
     */
    public function isExpired(): bool;

    /**
     * Return true if user account has to reset its password at next authentication.
     */
    public function isPasswordResetRequired(): bool;

    /**
     * Return true if password request is expired.
     */
    public function isPasswordRequestExpired(int $ttl): bool;
}
