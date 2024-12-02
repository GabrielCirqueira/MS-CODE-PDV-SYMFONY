<?php

namespace App\Repository;

use App\Entity\Carrinho;
use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function salvar(Item $item): void
    {
        $em = $this->getEntityManager();
        $em->persist($item);
        $em->flush();
    }

    public function buscar(Carrinho $carrinho): array
    {
        $qb = $this->createQueryBuilder('i')
            ->select('p.id AS produto_id','p.quantidade AS estoque', 'p.nome', 'i.quantidade', 'p.descricao', 'p.valorUnitario', 'c.nome AS categoria')
            ->innerJoin('i.produto', 'p')
            ->leftJoin('p.categoria', 'c')
            ->where('i.carrinho = :carrinho')
            ->setParameter('carrinho', $carrinho);
    
        return $qb->getQuery()->getArrayResult();
    }
}
