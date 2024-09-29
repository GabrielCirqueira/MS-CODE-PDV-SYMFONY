<?php

namespace App\Repository;

use App\Entity\Produtos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produtos>
 */
class ProdutosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produtos::class);
    }

    public function salvarProduto(Produtos $produtos) : void
    {
        $this->getEntityManager()->persist($produtos);
        $this->getEntityManager()->flush();
    }

    public function diminuirEstoque($id): bool
    {
        $produto = $this->find($id);
        
        if($produto->getQuantidade() == 0){
            return False;
        }

        $produto->setQuantidade($produto->getQuantidade() - 1);

        $this->getEntityManager()->persist($produto);
        $this->getEntityManager()->flush();

        return True;
    }

    public function aumentarEstoque($id): void
    {
        $produto = $this->find($id);
        $produto->setQuantidade($produto->getQuantidade() + 1);

        $this->getEntityManager()->persist($produto);
        $this->getEntityManager()->flush();
    }
}
