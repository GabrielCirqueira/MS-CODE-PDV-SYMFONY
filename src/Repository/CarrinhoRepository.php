<?php

namespace App\Repository;

use App\Entity\Carrinho;
use App\Entity\Cliente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Carrinho>
 */
class CarrinhoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Carrinho::class);
    }

    public function salvar(Carrinho $carrinho): int
    {
        $this->getEntityManager()->persist($carrinho);
        $this->getEntityManager()->flush();
        
        return $carrinho->getId();
    }

    public function buscar(Cliente $cliente): ?int
    {
        $query = $this->createQueryBuilder('carrinho')
            ->select('carrinho.id')
            ->where('carrinho.cliente = :cliente')
            ->andWhere('carrinho.status != :statusConcluido')
            ->andWhere('carrinho.status != :statusCancelado')
            ->setParameter('cliente', $cliente)
            ->setParameter('statusConcluido', 'Finalizado')
            ->setParameter('statusCancelado', 'Cancelado')
            ->setMaxResults(1)
            ->getQuery();
    
        $resultado = $query->getOneOrNullResult();
    
        return $resultado ? (int) $resultado['id'] : null;
    }
    
}
