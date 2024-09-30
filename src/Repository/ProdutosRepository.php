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

    public function excluirProduto($id): bool
    {
        $produto = $this->find($id);
        
        if($produto == NULL){
            return false;
        }

        $this->getEntityManager()->remove($produto);
        $this->getEntityManager()->flush();

        return True;
    }

    public function editarProduto($id,$dados): bool
    {
        $produto = $this->find($id);
        $produto->setNome($dados["nome"]);
        $produto->setDescricao($dados["descricao"]);
        $produto->setCategoria($dados["categoria"]);
        $produto->setQuantidade($dados["quantidade"]);
        $produto->setValor($dados["valor"]);

        $this->getEntityManager()->persist($produto);
        $this->getEntityManager()->flush();

        return True;
    }
}
