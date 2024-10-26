<?php

namespace App\Repository;

use App\Entity\Produto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produto>
 */
class ProdutoRepository extends ServiceEntityRepository
{
    private $CategoriaRepository;
    public function __construct(ManagerRegistry $registry, CategoriaRepository $categoriaRepository)
    {
        parent::__construct($registry, Produto::class);
        $this->CategoriaRepository = $categoriaRepository;
    }

    public function salvarProduto(Produto $produto) : void
    {
        $this->getEntityManager()->persist($produto);
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
        $categoria = $this->CategoriaRepository->find($dados["categoria"]);

        $produto->setNome($dados["nome"]);
        $produto->setCategoriaId($categoria);
        $produto->setQuantidade($dados["quantidade"]);
        $produto->setvalorUnitario($dados["valor"]);
        $produto->setDescricao($dados["descricao"]);

        $this->getEntityManager()->persist($produto);
        $this->getEntityManager()->flush();

        return True;
    }
}
 