<?php

namespace App\EntityListener;

use App\Entity\Category;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryEntityListener
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Category $category, LifecycleEventArgs $event)
    {
        $category->computeSlug($this->slugger);
        $category->setUpdatedAt(new \DateTimeImmutable('NOW'));
        $category->setCreatedAt(new \DateTimeImmutable('NOW'));
    }

    public function preUpdate(Category $category, LifecycleEventArgs $event)
    {
        $category->computeSlug($this->slugger);
        $category->setUpdatedAt(new \DateTimeImmutable('NOW'));
    }
}
