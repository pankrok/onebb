<?php

namespace App\Api;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use App\Entity\OneMessenger;
use App\Entity\Message;

class OneMessengerUserQueryExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    private $security;
    
    public function __construct(Security $security) 
    {
        $this->security = $security;
    }
    
    public function applyToCollection(QueryBuilder $qb, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {

        if (OneMessenger::class === $resourceClass) {
            $qb
                ->andWhere(":user MEMBER OF {$qb->getRootAliases()[0]}.users")
                ->setParameters(['user' => $this->security->getUser()])
            ;
        }
        
        if (Message::class === $resourceClass) {
            $qb
                ->leftJoin(OneMessenger::class, 'v', Join::WITH, "v.id = {$qb->getRootAliases()[0]}.om")
                ->andWhere(":user MEMBER OF v.users")
                ->setParameters(['user' => $this->security->getUser()])
            ;
        }
    }

    public function applyToItem(QueryBuilder $qb, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, string $operationName = null, array $context = [])
    {          
        if (OneMessenger::class === $resourceClass && $context["collection_operation_name"] !== "post") {
            $qb
                ->andWhere(":user MEMBER OF {$qb->getRootAliases()[0]}.users")
                ->setParameters(['user' => $this->security->getUser()])
            ;
        }
        
        if (Message::class === $resourceClass) {
            $qb
                ->leftJoin(OneMessenger::class, 'v', Join::WITH, "v.id = {$qb->getRootAliases()[0]}.om")
                ->andWhere(":user MEMBER OF v.users")
                ->setParameters(['user' => $this->security->getUser()])
            ;
        }
    }
}