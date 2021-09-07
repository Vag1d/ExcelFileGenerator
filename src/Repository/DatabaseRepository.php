<?php

namespace App\Repository;

use App\Entity\Database;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

/**
 * @method Database|null find($id, $lockMode = null, $lockVersion = null)
 * @method Database|null findOneBy(array $criteria, array $orderBy = null)
 * @method Database[]    findAll()
 * @method Database[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DatabaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Database::class);
    }

    public function findLatest($page = 1, ?string $searchQuery): Pagerfanta
    {
        $qb = $this->createQueryBuilder('d')
            ->addOrderBy('d.created_at', 'DESC')
            ->addOrderBy('d.id', 'DESC')
        ;

        if (!empty($searchQuery)) {
            $qb->andWhere('d.fullNameC30 like :searchQuery')
                ->setParameter('searchQuery', '%' . $searchQuery . '%');
        }

        $paginator = new Pagerfanta(new QueryAdapter($qb->getQuery()));
        $paginator->setMaxPerPage(10);
        $paginator->setCurrentPage($page);

        return $paginator;
    }
}
