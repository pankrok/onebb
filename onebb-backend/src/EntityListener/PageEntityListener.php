<?php

namespace App\EntityListener;

use App\Entity\Page;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class PageEntityListener
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Page $entity, LifecycleEventArgs $event)
    {
        $entity->computeSlug($this->slugger);
        $entity->setUpdatedAt(new \DateTimeImmutable('NOW'));
        $entity->setCreatedAt(new \DateTimeImmutable('NOW'));
    }

    public function preUpdate(Page $entity, LifecycleEventArgs $event)
    {
        $entity->computeSlug($this->slugger);
        $entity->setUpdatedAt(new \DateTimeImmutable('NOW'));
    }
}
