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

        return $this->json($produtos);
    }

    #[Route('/api/produtos/{id}', name: 'api_produto', methods: ['GET'])]
    public function buscarProduto($id, ProdutoRepository $produtoRepository): JsonResponse
    {
        $produto = $produtoRepository->find($id);
        if (!$produto) {
            return new JsonResponse([
                'error' => 'Produto não encontrado',
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        return $this->json($produto);
    }
}
