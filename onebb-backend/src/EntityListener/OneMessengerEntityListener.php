<?php

namespace App\EntityListener;

use App\Entity\OneMessenger;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Persistence\ManagerRegistry;

class OneMessengerEntityListener
{
    protected $em;
    
    public function __construct(ManagerRegistry $em)
    {
        $this->em = $em;
    }   
    
    public function prePersist(OneMessenger $entity, LifecycleEventArgs $event)
    {
        $msg = $this->em->getRepository(OneMessenger::class)->isExist($entity->getUsers());
        $entity->setUpdatedAt(new \DateTimeImmutable('NOW'));

        if ($msg !== null) {            
            $entity->setId($msg->getId());
            $entity->setUpdatedAt($msg->getUpdatedAt()); 
            $this->em->getManager()->persist($entity);
        }
    }

}
