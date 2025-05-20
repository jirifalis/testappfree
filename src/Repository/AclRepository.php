<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\ACL\Resource;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AclRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Resource::class);
    }

    public function findAccessibleResourcesByUserId(int $id): array
    {

        // todo: this should be rewritten, its just prototype
        $x = $this->getEntityManager()->getConnection()->executeQuery('

            select distinct r.id, r.name from users_groups as ug 
            join permissions as p on ug.group_id = p.group_id
            join resources as r on r.id = p.resource_id
            where ug.user_id = :user_id
            
        ',['user_id' => $id]);

        return $x->fetchAllKeyValue();
    }
}