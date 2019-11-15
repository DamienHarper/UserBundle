<?php

namespace DH\UserBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Security\Core\User\UserInterface;

class UserEvent extends Event
{
    protected $user;
    protected $data;

    /**
     * Constructor.
     *
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @param array                                               $data
     */
    public function __construct(?UserInterface $user = null, $data = null)
    {
        $this->user = $user;
        $this->data = null === $data || !\is_array($data) ? [] : $data;
    }

    /**
     * Returns user.
     *
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Returns data.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
