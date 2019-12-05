<?php

namespace App\DoctrineExtension;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use App\Entity\Patient;

class CurrentUserExtension implements QueryCollectionExtensionInterface
{
    protected $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        ?string $operationName = null
    ) {
        if ($resourceClass == Patient::class && $operationName === "get") {
            $alias = $queryBuilder->getRootAliases()[0];
            $queryBuilder->andWhere("$alias.doctor = :user OR $alias.nurses = :user")
                ->setParameter("user", $this->security->getUser());
        }
    }
}
