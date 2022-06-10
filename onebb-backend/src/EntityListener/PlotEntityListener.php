<?php

namespace App\EntityListener;

use App\Entity\Plot;
use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Persistence\ManagerRegistry;
use Exercise\HTMLPurifierBundle\HTMLPurifiersRegistryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class PlotEntityListener
{
    private $slugger;
    private $ti;
    private $em;
    private $hp;

    public function __construct(SluggerInterface $slugger, TokenStorageInterface $ti, ManagerRegistry $doctrine, HTMLPurifiersRegistryInterface $registry)
    {
        $this->slugger = $slugger;
        $this->ti = $ti;
        $this->em = $doctrine->getManager();
        $this->hp = $registry->get('default');
    }

    public function prePersist(Plot $entity, LifecycleEventArgs $event)
    {
        $user = $this->ti->getToken()->getUser();

        if (!$user instanceof User) {
            exit('{"error": "db error"}');
        }

        $name = $this->hp->purify($entity->getName());
        $user->incrementPlotsNo();

        $entity
            ->setUser($user)
            ->setLastActiveUser($user)
            ->setName($name)
            ->setUpdatedAt(new \DateTimeImmutable('NOW'))
            ->setCreatedAt(new \DateTimeImmutable('NOW'));
        $entity->computeSlug($this->slugger);
        $board = $entity->getBoard()->incrementPlotsNo();
    }

    public function preUpdate(Plot $entity, LifecycleEventArgs $event)
    {
        $entity->computeSlug($this->slugger);
        // $entity->setUpdatedAt(new \DateTimeImmutable('NOW'));
    }
}
