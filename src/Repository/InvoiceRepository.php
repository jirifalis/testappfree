<?php declare(strict_types=1);

namespace App\Repository;

use App\Controller\AccessControlTrait;
use App\Entity\Invoice;
use App\Exception\ForbiddenException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class InvoiceRepository extends ServiceEntityRepository
{

    use AccessControlTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invoice::class);
    }

    public function loadByCompanyName(string $name): ?Invoice
    {

        if(!$this->isAllowed('invoice')){
            throw new ForbiddenException('invoice');
        }
        return $this->createQueryBuilder('i')
            ->where('i.company_name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }
}