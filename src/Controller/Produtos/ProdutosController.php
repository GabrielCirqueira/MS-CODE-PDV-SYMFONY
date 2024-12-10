<?php

namespace App\Controller\Produtos;

use App\Repository\CategoriaRepository;
use App\Repository\ProdutoRepository;
use App\Service\ProdutoService;
use App\Service\VerificarPermissaoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ProdutosController extends AbstractController
{
    private $verificarPermissaoService;

    public function __construct(VerificarPermissaoService $verificarPermissaoService)
    {
        $this->verificarPermissaoService = $verificarPermissaoService;
    }

    #[Route('/produtos', name: 'produtos')]
    public function index(ProdutoRepository $produtoRepository): Response
    {
        if (!$this->verificarPermissaoService->execute('Ver-Produtos')) {
            return $this->render('Login/error.html.twig');
        }

        $produtos = $produtoRepository->findAll();
        return $this->render('produtos/produtos.html.twig', [
            "modo" => "adicionar",
            "produtos" => $produtos,
        ]);
    }

    #[Route('/produtos/adicionar', name: 'adicionarProdutos', methods: "GET")]
    public function adicionarProduto(CategoriaRepository $categoriaRepository): Response
    {
        if (!$this->verificarPermissaoService->execute('Adicionar-Produtos')) {
            return $this->render('Login/error.html.twig');
        }

        $categorias = $categoriaRepository->findAll();
        return $this->render('produtos/addProduto.html.twig', [
            'categorias' => $categorias,
            'modo' => "adicionar"
        ]);
    }

    #[Route('/produtos/adicionar', name: 'registrarProdutos', methods: "POST")]
    public function registrarProduto(Request $request, ProdutoService $produtoService): Response
    {
        $token = $request->request->get("_csrf_token");
        if (!$this->isCsrfTokenValid("addProduto", $token)) {
            $this->addFlash("danger", "Token CSRF inválido!");
            return $this->redirectToRoute("adicionarProdutos");
        }
    
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
                return $this->redirectToRoute("adicionarProdutos");
            }
        }
    
        $dados['valor'] = (int) $dados['valor'] * 100;
    
        $inserir = $produtoService->registrarProduto($dados);
    
        if (!$inserir) {
            $this->addFlash("danger", "Erro ao enviar formulário!");
            return $this->redirectToRoute("adicionarProdutos");
        }
    
        $this->addFlash("success", "Produto Adicionado com sucesso!");
        return $this->redirectToRoute("produtos");
    }
    
}