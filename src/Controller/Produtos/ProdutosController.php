<?php

namespace App\Controller\Produtos;

use App\Entity\Produtos;
use App\Repository\CategoriasRepository;
use App\Repository\ProdutosRepository;
use App\Service\ProdutoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProdutosController extends AbstractController
{
    #[Route('/produtos', name: 'app_produtos')]
    public function index(ProdutosRepository $produtosRepository): Response
    {
        $produtos = $produtosRepository->findAll();
        return $this->render('produtos/produtos.html.twig',[
            "produtos" => $produtos
        ]);
    }

    #[Route('/produtos/adicionar', name: 'app_adicionarProdutos')]
    public function adicionarProduto(CategoriasRepository $categoriasRepository): Response
    {
        $categorias = $categoriasRepository->findAll();
        return $this->render('produtos/addProduto.html.twig',[
            'categorias' => $categorias
        ]);
    }

    #[Route('/produtos/adicionar/registrar', name: 'app_registrarProdutos', methods: "POST")]
    public function registrarProduto(Request $request, ProdutoService $produtoService): Response
    {
 
        $token = $request->request->get("_csrf_token");

        if(!$request->isMethod("POST")){
            $this->addFlash("danger","Erro ao enviar formulário! 1");
            return $this->redirectToRoute("app_adicionarProdutos");
        }

        if(!$this->isCsrfTokenValid("addProduto",$token)){
            $this->addFlash("danger","Token CRSF inválido!");
            return $this->redirectToRoute("app_adicionarProdutos");
        }

        $dados = [
            "nome" => $request->request->get("nome"),
            "descricao" => $request->request->get("descricao"),
            "categoria" => $request->request->get("categoria"),
            "quantidade" => $request->request->get("quantidade"),
            "valor" => $request->request->get("valor")
        ];

        $inserir = $produtoService->registrarProduto($dados);

        if(!$inserir){
            $this->addFlash("danger","Erro ao enviar formulário!");
            return $this->redirectToRoute("app_adicionarProdutos");
        }

        $this->addFlash("success","Produto Adicionado com sucesso!");
        return $this->redirectToRoute("app_adicionarProdutos");

    }

}
