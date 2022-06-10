<?php

namespace App\EntityListener;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Persistence\ManagerRegistry;
use Exercise\HTMLPurifierBundle\HTMLPurifiersRegistryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PostEntityListener
{
    private $ti;
    private $em;
    private $hp;

    public function __construct(TokenStorageInterface $ti, ManagerRegistry $doctrine, HTMLPurifiersRegistryInterface $registry)
    {
        $this->ti = $ti;
        $this->em = $doctrine->getManager();
        $this->hp = $registry->get('default');
    }

    public function prePersist(Post $entity, LifecycleEventArgs $event)
    {
        $user = $this->ti->getToken()->getUser();

        if (!$user instanceof User) {
            exit('{"error": "db error"}');
        }

        $user->incrementPostsNo();
        $content = $this->hp->purify($entity->getContent());

        $entity->setUser($user)
            ->setReputation(0)
            ->setHidden(false)
            ->setContent($content)
            ->setUpdatedAt(new \DateTimeImmutable('NOW'))
            ->setCreatedAt(new \DateTimeImmutable('NOW'))
        ;
        $plot = $entity->getPlot()
            ->incrementPostNo()
            ->setLastActiveUser($user)
            ->setUpdatedAt(new \DateTimeImmutable('NOW'))
        ;

        $board = $plot->getBoard()
            ->incrementPostsNo()
            ->setLastActiveUser($user)
        ;
    }

    public function preUpdate(Post $entity, LifecycleEventArgs $event)
    {
        $entity->setUpdatedAt(new \DateTimeImmutable('NOW'));
    }
}
