<?php 

namespace App\Service;

use App\Entity\Produto;
use App\Repository\CategoriaRepository;
use App\Repository\ProdutoRepository;

class ProdutoService 
{
    private ProdutoRepository $produtoRepository;
    private CategoriaRepository $categoriaRepository;

    public function __construct(ProdutoRepository $produtoRepository, CategoriaRepository $categoriaRepository)
    {
        $this->produtoRepository = $produtoRepository;
        $this->categoriaRepository = $categoriaRepository;
    }

    public function registrarProduto($dados): bool
    {
        $produto = new Produto;
        $categoria = $this->categoriaRepository->find($dados["categoria"]);

        $produto->setNome($dados["nome"]);
        $produto->setCategoriaId($categoria);
        $produto->setQuantidade($dados["quantidade"]);
        $produto->setvalorUnitario($dados["valor"]);
        $produto->setDescricao($dados["descricao"]);

        $this->produtoRepository->salvarProduto($produto);

        return True;
    }
}
