<?php

namespace App\EntityListener;

use App\Entity\Group;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class GroupEntityListener
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Group $entity, LifecycleEventArgs $event)
    {
        $entity->setUpdatedAt(new \DateTimeImmutable('NOW'));
        $entity->setCreatedAt(new \DateTimeImmutable('NOW'));
    }

    public function preUpdate(Group $entity, LifecycleEventArgs $event)
    {
        $entity->setUpdatedAt(new \DateTimeImmutable('NOW'));
    }
}
