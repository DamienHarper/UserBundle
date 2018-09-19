<?php

namespace DH\UserBundle\Model;

use Doctrine\ORM\Mapping as ORM;

trait ExtendedUserTrait
{
    /**
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];

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
     * Get the value of username.
     *
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username.
     *
     * @param mixed $username
     *
     * @return UserTrait
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of password.
     *
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password.
     *
     * @param mixed $password
     *
     * @return UserTrait
     */
    public function setPassword($password)
    {
        $this->password = $password;

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
     *
     * @return UserTrait
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get the value of password_requested_at.
     *
     * @return mixed
     */
    public function getPasswordRequestedAt(): ?\DateTime
    {
        return $this->password_requested_at;
    }

    /**
     * Set the value of password_requested_at.
     *
     * @param mixed $passwordRequestedAt
     *
     * @return UserTrait
     */
    public function setPasswordRequestedAt(?\DateTime $passwordRequestedAt)
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
     *
     * @return UserTrait
     */
    public function setResetToken(?string $resetToken)
    {
        $this->reset_token = $resetToken;

        return $this;
    }

    /**
     * Get the value of expired_at.
     *
     * @return mixed
     */
    public function getExpiredAt(): ?\DateTime
    {
        return $this->expired_at;
    }

    /**
     * Set the value of expired_at.
     *
     * @param mixed $expiredAt
     *
     * @return UserTrait
     */
    public function setExpiredAt(?\DateTime $expiredAt)
    {
        $this->expired_at = $expiredAt;

        return $this;
    }

    /**
     * Get the value of deleted_at.
     *
     * @return mixed
     */
    public function getDeletedAt(): ?\DateTime
    {
        return $this->deleted_at;
    }

    /**
     * Set the value of deleted_at.
     *
     * @param mixed $deletedAt
     *
     * @return UserTrait
     */
    public function setDeletedAt(?\DateTime $deletedAt)
    {
        $this->deleted_at = $deletedAt;

        return $this;
    }

    /**
     * Get the value of is_locked.
     *
     * @return mixed
     */
    public function isLocked(): bool
    {
        return $this->is_locked;
    }

    /**
     * Set the value of is_locked.
     *
     * @param mixed $isLocked
     *
     * @return UserTrait
     */
    public function setIsLocked($isLocked)
    {
        $this->is_locked = $isLocked;

        return $this;
    }

    /**
     * Get the value of is_password_reset_required.
     *
     * @return mixed
     */
    public function isPasswordResetRequired(): bool
    {
        return $this->is_password_reset_required;
    }

    /**
     * Set the value of is_password_reset_required.
     *
     * @param mixed $isPasswordResetRequired
     *
     * @return UserTrait
     */
    public function setIsPasswordResetRequired($isPasswordResetRequired)
    {
        $this->is_password_reset_required = $isPasswordResetRequired;

        return $this;
    }

    /**
     * Sets plain-text password.
     *
     * @param $plainPassword
     *
     * @return $this
     */
    public function setPlainPassword($plainPassword)
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
     *
     * @param int $ttl
     *
     * @return bool
     */
    public function isPasswordRequestExpired(int $ttl): bool
    {
        return null === $this->getPasswordRequestedAt() || $this->getPasswordRequestedAt()->getTimestamp() + $ttl <= time();
    }

    /**
     * Checks if a user account is deleted.
     *
     * @return bool
     */
    public function isDeleted(): bool
    {
        return null !== $this->getDeletedAt() && $this->getDeletedAt()->getTimestamp() <= time();
    }

    /**
     * Checks if a user account is expired.
     *
     * @return bool
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
    public function unserialize($serialized)
    {
        [
            $this->id,
            $this->username,
            $this->password,
            $this->expired_at,
            $this->deleted_at,
            $this->is_locked,
        ] = unserialize($serialized, ['allowed_classes' => false]);
    }
}
