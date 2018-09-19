<?php

namespace DH\UserBundle\Security;

use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

interface UserInterface extends BaseUserInterface
{
    /**
     * Get the value of email.
     *
     * @return null|string
     */
    public function getEmail(): ?string;

    /**
     * Get the value of password_requested_at.
     *
     * @return null|\DateTime
     */
    public function getPasswordRequestedAt(): ?\DateTime;

    /**
     * Set the value of password_requested_at.
     *
     * @param null|\DateTime $passwordRequestedAt
     *
     * @return self
     */
    public function setPasswordRequestedAt(?\DateTime $passwordRequestedAt);

    /**
     * Returns plain-text password.
     *
     * @return null|string
     */
    public function getPlainPassword(): ?string;

    /**
     * Sets plain-text password.
     *
     * @param null|string $plainPassword
     *
     * @return $this
     */
    public function setPlainPassword(?string $plainPassword);

    /**
     * Get the value of reset_token.
     *
     * @return null|string
     */
    public function getResetToken(): ?string;

    /**
     * Set the value of reset_token.
     *
     * @param null|string $token
     *
     * @return self
     */
    public function setResetToken(?string $token);

    /**
     * Return true if user account is locked.
     *
     * @param int $ttl
     *
     * @return bool
     */
    public function isLocked(): bool;

    /**
     * Return true if user account is deleted.
     *
     * @param int $ttl
     *
     * @return bool
     */
    public function isDeleted(): bool;

    /**
     * Return true if user account is expired.
     *
     * @param int $ttl
     *
     * @return bool
     */
    public function isExpired(): bool;

    /**
     * Return true if user account has to reset its password at next authentication.
     *
     * @param int $ttl
     *
     * @return bool
     */
    public function isPasswordResetRequired(): bool;

    /**
     * Return true if password request is expired.
     *
     * @param int $ttl
     *
     * @return bool
     */
    public function isPasswordRequestExpired(int $ttl): bool;
}
