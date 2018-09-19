<?php

namespace DH\UserBundle\Exception;

use Symfony\Component\Security\Core\Exception\AccountStatusException;

class AccountLockedException extends AccountStatusException
{
    /**
     * {@inheritdoc}
     */
    public function getMessageKey()
    {
        return 'login.exception.locked_account';
    }
}
