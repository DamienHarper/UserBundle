<?php

namespace DH\UserBundle\Event;

use DH\UserBundle\Model\UserManager;
use DH\UserBundle\Security\UserInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

class UserEventSubscriber implements EventSubscriber
{
    /**
     * @var UserManager
     */
    private $manager;

    public function __construct(UserManager $manager)
    {
        $this->manager = $manager;
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
            $this->manager->hashPassword($object);
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if ($object instanceof UserInterface) {
            $this->manager->hashPassword($object);
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
}