<?php

namespace App\Repository;

use App\Entity\Categorias;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Categorias>
 */
class CategoriasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorias::class);
    }

    //    /**
    //     * @return Categorias[] Returns an array of Categorias objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Categorias
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function salvarUsuario(Categorias $categoria) : void
    {
        $this->getEntityManager()->persist($categoria);
        $this->getEntityManager()->flush();
    }

    public function buscarCategoria($nome) : Categorias | false
    {
        $query = $this->createQueryBuilder("categoria")
        ->andWhere("categoria.nome = :nome")
        ->setParameter("nome",$nome)
        ->getQuery();

        $categoria = $query->getOneOrNullResult();

        return $categoria ? $categoria : False;
    }

}