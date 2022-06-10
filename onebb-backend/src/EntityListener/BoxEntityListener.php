<?php

namespace App\EntityListener;

use App\Entity\Box;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class BoxEntityListener
{
    public function prePersist(Box $entity, LifecycleEventArgs $event)
    {
        $entity
            ->setEngine($entity->getEngine() ?? 'CustomBox')
            ->setUpdatedAt(new \DateTimeImmutable('NOW'))
            ->setCreatedAt(new \DateTimeImmutable('NOW'))
        ;
    }

    public function preUpdate(Box $entity, LifecycleEventArgs $event)
    {
        $entity
            ->setUpdatedAt(new \DateTimeImmutable('NOW'))
        ;
    }
}
