<?php

namespace DH\UserBundle\Model;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait ExtendedUserTrait
{
    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default": NULL})
     */
    protected $password_requested_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default": NULL})
     */
    protected $reset_token;

    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default": NULL})
     */
    protected $expired_at;

    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default": NULL})
     */
    protected $deleted_at;

    /**
     * @ORM\Column(type="boolean", options={"default": "0"})
     */
    protected $is_locked = false;

    /**
     * @ORM\Column(type="boolean", options={"default": "0"})
     */
    protected $is_password_reset_required = false;

    /**
     * @var string
     */
    protected $plain_password;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank
     */
    private $password;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank
     * @Assert\Email
     */
    private $email;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];

    /**
     * Get the value of username.
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Set the value of username.
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of password.
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set the value of password.
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of email.
     *
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set the value of email.
     *
     * @return UserTrait
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of roles.
     *
     * @return mixed
     */
    public function getRoles()
    {
//        return array_unique(array_merge(['ROLE_USER'], $this->roles));
        return $this->roles;
    }

    /**
     * Set the value of roles.
     *
     * @param mixed $roles
     */
    public function setRoles($roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get the value of password_requested_at.
     *
     * @return mixed
     */
    public function getPasswordRequestedAt(): ?DateTimeInterface
    {
        return $this->password_requested_at;
    }

    /**
     * Set the value of password_requested_at.
     */
    public function setPasswordRequestedAt(?DateTimeInterface $passwordRequestedAt): self
    {
        $this->password_requested_at = $passwordRequestedAt;

        return $this;
    }

    /**
     * Get the value of reset_token.
     *
     * @return mixed
     */
    public function getResetToken(): ?string
    {
        return $this->reset_token;
    }

    /**
     * Set the value of reset_token.
     *
     * @param mixed $resetToken
     */
    public function setResetToken(?string $resetToken): UserTrait
    {
        $this->reset_token = $resetToken;

        return $this;
    }

    /**
     * Get the value of expired_at.
     *
     * @return mixed
     */
    public function getExpiredAt(): ?DateTimeInterface
    {
        return $this->expired_at;
    }

    /**
     * Set the value of expired_at.
     */
    public function setExpiredAt(?DateTimeInterface $expiredAt): self
    {
        $this->expired_at = $expiredAt;

        return $this;
    }

    /**
     * Get the value of deleted_at.
     */
    public function getDeletedAt(): ?DateTimeInterface
    {
        return $this->deleted_at;
    }

    /**
     * Set the value of deleted_at.
     */
    public function setDeletedAt(?DateTimeInterface $deletedAt): self
    {
        $this->deleted_at = $deletedAt;

        return $this;
    }

    /**
     * Get the value of is_locked.
     */
    public function isLocked(): bool
    {
        return $this->is_locked;
    }

    /**
     * Set the value of is_locked.
     */
    public function setIsLocked(bool $isLocked): self
    {
        $this->is_locked = $isLocked;

        return $this;
    }

    /**
     * Get the value of is_password_reset_required.
     */
    public function isPasswordResetRequired(): bool
    {
        return $this->is_password_reset_required;
    }

    /**
     * Set the value of is_password_reset_required.
     */
    public function setIsPasswordResetRequired(bool $isPasswordResetRequired): self
    {
        $this->is_password_reset_required = $isPasswordResetRequired;

        return $this;
    }

    /**
     * Sets plain-text password.
     */
    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plain_password = $plainPassword;

        return $this;
    }

    /**
     * Returns plain-text password.
     *
     * @return string
     */
    public function getPlainPassword(): ?string
    {
        return $this->plain_password;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string The salt
     */
    public function getSalt(): string
    {
        return '';
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        $this->plain_password = null;

        return $this;
    }

    /**
     * Checks if password request is expired.
     */
    public function isPasswordRequestExpired(int $ttl): bool
    {
        return null === $this->getPasswordRequestedAt() || $this->getPasswordRequestedAt()->getTimestamp() + $ttl <= time();
    }

    /**
     * Checks if a user account is deleted.
     */
    public function isDeleted(): bool
    {
        return null !== $this->getDeletedAt() && $this->getDeletedAt()->getTimestamp() <= time();
    }

    /**
     * Checks if a user account is expired.
     */
    public function isExpired(): bool
    {
        return null !== $this->getExpiredAt() && $this->getExpiredAt()->getTimestamp() <= time();
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
            $this->expired_at,
            $this->deleted_at,
            $this->is_locked,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized): void
    {
        list(
            $this->id,
            $this->username,
            $this->password,
            $this->expired_at,
            $this->deleted_at,
            $this->is_locked) = unserialize($serialized, ['allowed_classes' => false]);
    }
}
