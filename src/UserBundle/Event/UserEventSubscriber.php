<?php

namespace DH\UserBundle\Event;

use DH\UserBundle\Security\UserInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class UserEventSubscriber implements EventSubscriber
{
    /**
     * @var PasswordEncoderInterface
     */
    private $encoderFactory;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            'preUpdate',
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if ($object instanceof UserInterface) {
            $this->hashPassword($object);
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if ($object instanceof UserInterface) {
            $this->hashPassword($object);
            $meta = $args
                ->getObjectManager()
                ->getClassMetadata(get_class($object))
            ;
            $args
                ->getObjectManager()
                ->getUnitOfWork()
                ->recomputeSingleEntityChangeSet($meta, $object)
            ;
        }
    }

    private function hashPassword(UserInterface $user)
    {
        $plainPassword = $user->getPlainPassword();
        if (0 !== strlen($plainPassword)) {
            $salt = null;
            if (!($this->encoderFactory->getEncoder($user) instanceof BCryptPasswordEncoder)) {
                // salt is not used by bcrypt encoder
                $salt = rtrim(str_replace('+', '.', base64_encode(random_bytes(32))), '=');
            }
            $user->setSalt($salt);

            $hashedPassword = $this->encoderFactory->getEncoder($user)->encodePassword($plainPassword, $user->getSalt());
            $user->setPassword($hashedPassword);
            $user->eraseCredentials();
        }
    }
}