<?php

namespace App\EntityListener;

use App\Entity\Board;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class BoardEntityListener
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Board $entity, LifecycleEventArgs $event)
    {
        $entity->computeSlug($this->slugger);
        $entity->setUpdatedAt(new \DateTimeImmutable('NOW'));
        $entity->setCreatedAt(new \DateTimeImmutable('NOW'));
    }

    public function preUpdate(Board $entity, LifecycleEventArgs $event)
    {
        $entity->computeSlug($this->slugger);
        $entity->setUpdatedAt(new \DateTimeImmutable('NOW'));
    }
}
