<?php

namespace App\Api;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Category;
use App\Entity\Page;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class FilterActiveQueryExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    private $admin = false;

    public function __construct(TokenStorageInterface $ti)
    {
        $this->admin = false;
        $token = $ti->getToken();
        if (null !== $token) {
            $this->admin = $token->getUser()->getAcpEnable();
        }
    }

    public function applyToCollection(QueryBuilder $qb, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if (
                (
                    Category::class === $resourceClass
                    || Page::class === $resourceClass
                )
            && false === $this->admin
        ) {
            $qb->andWhere(sprintf('%s.active = true', $qb->getRootAliases()[0]));
        }
    }

    public function applyToItem(QueryBuilder $qb, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, string $operationName = null, array $context = [])
    {
        if (
                (
                    Category::class === $resourceClass
                    || Page::class === $resourceClass
                )
            && false === $this->admin
        ) {
            $qb->andWhere(sprintf('%s.active = true', $qb->getRootAliases()[0]));
        }
    }
}
