<?php

namespace App\Controller\Produtos;

use App\Repository\ProdutoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProdutoApiController extends AbstractController
{
    #[Route('/api/produtos', name: 'api_produtos', methods: ['GET'])]
    public function listarProdutos(Request $request, ProdutoRepository $produtoRepository): JsonResponse
    {
        $nome = $request->query->get('nome');
        
        $produtos = $produtoRepository->buscarProdutosAtivos($nome);

        $dados = array_map(fn($produto) => [
            'nome' => $produto->getNome(),
            'quantidade' => $produto->getQuantidade(),
            'valorUnitario' => $produto->getValorUnitario(),
        ], $produtos);

        return $this->json($dados);
    }
}
