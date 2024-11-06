<?php

namespace App\Controller\Produtos;

use App\Entity\Produtos;
use App\Repository\CategoriaRepository;
use App\Repository\ProdutoRepository;
use App\Repository\VendasRepository;
use App\Service\ProdutoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EstoqueController extends AbstractController
{
    #[Route('/produtos/vender/{id}', name: 'venderProduto')]
    public function venderProduto(int $id, ProdutoRepository $produtoRepository): Response
    {
        $produto = $produtoRepository->find($id);
        if (!$produto) {
            $this->addFlash("danger", "Produto não encontrado.");
            return $this->redirectToRoute("produtos");
        }

        return $this->render('vendas/vender.html.twig', [
            "id" => $id,
            "nomeProduto" => $produto->getNome(),
            "valorProduto" => $produto->getValorUnitario(),
            "categoriaProduto" => $produto->getCategoriaId(),
        ]);
    }

    #[Route('/produtos/vender/registrar/{id}', name: 'vendaRegistrar')]
    public function venderProdutoRegistrar(int $id, ProdutoRepository $produtoRepository, Request $request, VendasRepository $vendasRepository): Response
    {
        $produto = $produtoRepository->find($id);
        if (!$produto) {
            $this->addFlash("danger", "Produto não encontrado.");
            return $this->redirectToRoute("produtos");
        }

        $diminuir = $produtoRepository->diminuirEstoque($id);
        $nomeCliente = $request->request->get("nomeComprador");

        if (!$diminuir) {
            $this->addFlash("danger", "O produto {$produto->getNome()} não pode ser diminuído no estoque.");
            return $this->redirectToRoute("produtos");
        }

        $vendasRepository->inserir("O cliente {$nomeCliente} comprou o produto {$produto->getNome()} e foi diminuído 1 do estoque.");

        $this->addFlash("success", "O cliente {$nomeCliente} comprou o produto {$produto->getNome()} com sucesso.");
        return $this->redirectToRoute("produtos");
    }

    #[Route('produtos/quantidade/aumentar/{id}', name: 'aumentarQuantidadeProduto')]
    public function aumentarQuantidade(int $id, ProdutoRepository $produtoRepository, VendasRepository $vendasRepository): Response
    {
        $produto = $produtoRepository->find($id);
        if (!$produto) {
            $this->addFlash("danger", "Produto não encontrado.");
            return $this->redirectToRoute("produtos");
        }

        $produtoRepository->aumentarEstoque($id);
        $vendasRepository->inserir("O produto {$produto->getNome()} foi aumentado no estoque.");

        $this->addFlash("success", "O produto {$produto->getNome()} foi aumentado no estoque.");
        return $this->redirectToRoute("produtos");
    }

    #[Route('produtos/vendas', name: 'vendas')]
    public function vendas(VendasRepository $vendasRepository): Response
    {
        $vendas = $vendasRepository->getVendas();
        return $this->render("vendas/vendas.html.twig",[
            "vendas" => $vendas
        ]);
    }
}