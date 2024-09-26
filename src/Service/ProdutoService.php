<?php 

namespace App\Service;

use App\Entity\Produtos;
use App\Repository\ProdutosRepository;

class ProdutoService 
{

    public function __construct(private ProdutosRepository $ProdutoRepository)
    {
    }

    public function registrarProduto($dados): bool
    {
        $produto = new Produtos;

        $produto->setNome($dados["nome"]);
        $produto->setCategoria($dados["categoria"]);
        $produto->setQuantidade($dados["quantidade"]);
        $produto->setValor($dados["valor"]);
        $produto->setDescricao($dados["descricao"]);

        $this->ProdutoRepository->salvarProduto($produto);

        return True;
    }
}
