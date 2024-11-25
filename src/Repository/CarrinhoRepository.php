<?php

namespace App\Repository;

use App\Entity\Carrinho;
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

    public function salvar(Carrinho $carrinho) : void
    {
        $this->getEntityManager()->persist($carrinho);
        $this->getEntityManager()->flush();
    }

}
