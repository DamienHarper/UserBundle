<?php

namespace DH\UserBundle\Event;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\EventDispatcher\Event;

class UserEvent extends Event
{
    protected $user;
    protected $data;

    public function __construct(?UserInterface $user = null, ?array $data = null)
    {
        $this->user = $user;
        $this->data = null === $data || !\is_array($data) ? [] : $data;
    }

    /**
     * Returns user.
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }

    /**
     * Returns data.
     */
    public function getData(): array
    {
        return $this->data;
    }
}
