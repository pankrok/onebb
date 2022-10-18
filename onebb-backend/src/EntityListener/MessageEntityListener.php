<?php

namespace App\EntityListener;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MessageEntityListener
{
    protected $em;
    protected $ti;
    
    public function __construct(ManagerRegistry $em, TokenStorageInterface $ti)
    {
        $this->em = $em;
        $this->ti = $ti;
    }   
    
    public function prePersist(Message $entity, LifecycleEventArgs $event)
    {
        
        $user = $this->ti->getToken()->getUser();

        if (!$user instanceof User) {
            exit('{"error": "invalid user"}');
        }
        
        $entity->setCreatedAt(new \DateTimeImmutable('NOW'));
        $entity->setSender($user);
        $om = $entity->getOm();
        $om->setUpdatedAt(new \DateTimeImmutable('NOW'));
        
        $this->em->getManager()->persist($om);

    }

}
