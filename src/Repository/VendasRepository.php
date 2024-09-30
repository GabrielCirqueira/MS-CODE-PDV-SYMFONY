<?php

namespace App\Repository;

use App\Entity\Vendas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vendas>
 */
class VendasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vendas::class);
    }

    //    /**
    //     * @return Vendas[] Returns an array of Vendas objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Vendas
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function inserir($string): void
    {
        $inserir = new Vendas;
        $inserir->setStatus($string);

        $inserir->setData(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

        $this->getEntityManager()->persist($inserir);
        $this->getEntityManager()->flush();
    }

    public function getVendas(): array
    {
        return $this->createQueryBuilder("vendas")
        ->orderBy("vendas.data","DESC")
        ->getQuery()
        ->getResult();
    }
}
