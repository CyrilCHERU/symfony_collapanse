<?php

namespace App\DoctrineExtension;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use App\Entity\Care;
use App\Entity\Intervention;
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
            $queryBuilder->andWhere("$alias.doctor = :user OR n = :user")
                ->join("$alias.nurses", "n")
                ->setParameter("user", $this->security->getUser());
            return;
        }
        if ($resourceClass == Care::class && $operationName === "get") {
            $alias = $queryBuilder->getRootAliases()[0];
            $queryBuilder->join("$alias.patient", 'p')
                ->join("p.nurses", "n")
                ->andWhere("p.doctor = :user OR n = :user")
                ->setParameter("user", $this->security->getUser());

            return;
        }

        // if ($resourceClass == Care::class && $operationName === 'get') {
        //     $alias = $queryBuilder->getRootAliases()[0];
        //     $queryBuilder->join("$alias.care", "c")
        //         ->join("c.patient", "p")
        //         ->andWhere("p.doctor = :user OR n = :user")
        //         ->setParameter("user", $this->security->getUser());
        // }
    }
}
