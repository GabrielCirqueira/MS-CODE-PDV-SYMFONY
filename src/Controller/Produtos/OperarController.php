<?php

namespace App\Controller\Produtos;

use App\Repository\CategoriaRepository;
use App\Repository\ProdutoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OperarController extends AbstractController
{
    #[Route('/produtos/excluir/{id}/{nome}', name: 'excluirProduto')]
    public function excluirProduto(int $id, ProdutoRepository $produtoRepository): Response
    {
        $excluir = $produtoRepository->excluirProduto($id);

        if (!$excluir) {
            $this->addFlash('danger', "O Produto de ID {$id} não existe!");
            return $this->redirectToRoute("produtos");
        }

        $this->addFlash('success', "O produto foi excluído com sucesso!");
        return $this->redirectToRoute("produtos");
    }

    #[Route('/produtos/editar/{id}', name: 'editarProduto')]
    public function editarProduto(int $id, ProdutoRepository $produtoRepository, CategoriaRepository $categoriaRepository): Response
    {
        $produto = $produtoRepository->find($id);
        if (!$produto) {
            $this->addFlash('danger', "Produto inexistente.");
            return $this->redirectToRoute("produtos");
        }

        $categorias = $categoriaRepository->findAll();

        return $this->render('produtos/addProduto.html.twig', [
            "categorias" => $categorias,
            "modo" => "editar",
            "produto" => $produto,
            "id" => $id
        ]);
    }

    #[Route('/produtos/registrar/editar', name: 'registrarEditarProduto')]
    public function registrarEditarProduto(Request $request, ProdutoRepository $produtoRepository): Response
    {
        $token = $request->request->get("_csrf_token");
        if (!$this->isCsrfTokenValid("editProduto", $token)) {
            $this->addFlash("danger", "Token CSRF inválido!");
            return $this->redirectToRoute("produtos");
        }

        $id = $request->request->get("id");
        $dados = [
            "nome" => trim($request->request->get("nome", "")),
            "descricao" => trim($request->request->get("descricao", "")),
            "categoria" => trim($request->request->get("categoria", "")),
            "quantidade" => trim($request->request->get("quantidade", "")),
            "valor" => trim($request->request->get("valor", ""))
        ];
    
        foreach ($dados as $campo => $valor) {
            if (empty($valor)) {
                $this->addFlash("danger", "O campo $campo está vazio!");
                return $this->redirectToRoute("editarProduto", compact('id'));
            }
        }
    
        $dados['valor'] = (int) $dados['valor'] * 100;

        $editar = $produtoRepository->editarProduto($id, $dados);

        if ($editar) {
            $this->addFlash('success', "Produto editado com sucesso.");
        } else {
            $this->addFlash('danger', "Ocorreu um erro ao editar o produto.");
        }

        return $this->redirectToRoute("produtos");
    }

    #[Route(path: '/produtos/inativar/{id}', name: 'inativarProduto')]
    public function inativar($id, ProdutoRepository $produtoRepository): Response
    {
        $Produto = $produtoRepository->find($id);

        if (!$Produto) {
            $this->addFlash('error', "Usuário não encontrado.");
            return $this->redirectToRoute("produtos");
        }

        $Produto->setAtivo(false);
        $produtoRepository->salvarProduto($Produto);

        $this->addFlash('success', "Usuário {$Produto->getNome()} foi inativado com sucesso.");
        return $this->redirectToRoute("produtos");
    }

    #[Route(path: '/produtos/ativar/{id}', name: 'ativarProduto')]
    public function ativar($id, ProdutoRepository $produtoRepository): Response
    {
        $Produto = $produtoRepository->find($id);

        if (!$Produto) {
            $this->addFlash('error', "Usuário não encontrado.");
            return $this->redirectToRoute("produtos");
        }

        $Produto->setAtivo(true);
        $produtoRepository->salvarProduto($Produto);

        $this->addFlash('success', "Usuário {$Produto->getNome()} foi ativado com sucesso.");
        return $this->redirectToRoute("produtos");
    }


}