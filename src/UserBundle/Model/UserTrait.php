<?php

namespace DH\UserBundle\Model;

trait UserTrait
{
    /**
     * @var string
     */
    protected $plain_password;

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
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
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
        ] = unserialize($serialized, ['allowed_classes' => false]);
    }
}
