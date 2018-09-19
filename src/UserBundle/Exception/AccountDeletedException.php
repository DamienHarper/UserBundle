<?php

namespace DH\UserBundle\Exception;

use Symfony\Component\Security\Core\Exception\AccountStatusException;

class AccountDeletedException extends AccountStatusException
{
    /**
     * {@inheritdoc}
     */
    public function getMessageKey()
    {
        return 'login.exception.deleted_account';
    }
}
