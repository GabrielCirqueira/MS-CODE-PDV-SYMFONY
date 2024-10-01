<?php

namespace App\Controller\Produtos;

use App\Entity\Produtos;
use App\Repository\CategoriasRepository;
use App\Repository\ProdutosRepository;
use App\Repository\VendasRepository;
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
            "modo" => "adicionar",
            "produtos" => $produtos,
        ]);
    }


    #[Route('/produtos/adicionar', name: 'app_adicionarProdutos', methods: "GET")]
    public function adicionarProduto(CategoriasRepository $categoriasRepository): Response
    {
        $categorias = $categoriasRepository->findAll();
        return $this->render('produtos/addProduto.html.twig',[
            'categorias' => $categorias,
            'modo' => "adicionar"
        ]);
    }


    #[Route('/produtos/adicionar', name: 'app_registrarProdutos', methods: "POST")]
    public function registrarProduto(Request $request, ProdutoService $produtoService): Response
    {
 
        $token = $request->request->get("_csrf_token");

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


    #[Route('/produtos/vender/{id}', name: 'app_VenderProdutos')]
    public function venderProduto($id, ProdutosRepository $produtosRepository): Response
    {   
        $produto = $produtosRepository->find($id);
        return $this->render('vendas/vender.html.twig',[
            "id"    => $id,
            "nomeProduto" => $produto->getNome(),
            "valorProduto" => $produto->getvalor(),
            "categoriaProduto" => $produto->getCategoria(),
        ]);
    }
 

    #[Route('/produtos/vender/registrar/{id}', name: 'app_vendaRegistrar')]
    public function venderProdutoRegistrar($id, ProdutosRepository $produtosRepository, Request $request, VendasRepository $vendasRepository): Response
    {
        $diminuir = $produtosRepository->diminuirEstoque($id);
        $nomeProduto = $produtosRepository->find($id)->getNome();
        $nomeCliente = $request->request->get("nomeComprador");

        if(!$diminuir){
            $this->addFlash("danger","O produto {$nomeProduto} não pode ser diminuido o estoque pois está com 0.");
            return $this->redirectToRoute("app_produtos");
        }

        $vendasRepository->inserir("O cliente {$nomeCliente} comprou o produto {$nomeProduto} e foi diminuido 1 do estoque.");

        $this->addFlash("success","O cliente {$nomeCliente} comprou o produto {$nomeProduto} com sucesso.");
        return $this->redirectToRoute("app_produtos");
    }


    // #[Route('produtos/quantidade/diminuir/{id}', name: 'app_DiminuirQuantidadeProdutos')]
    // public function dimiuirQuantidade($id, ProdutosRepository $produtosRepository, VendasRepository $vendasRepository): Response
    // {
    //     $diminuir = $produtosRepository->diminuirEstoque($id);
    //     $nome = $produtosRepository->find($id)->getNome();

    //     if(!$diminuir){
    //         $this->addFlash("danger","O produto {$nome} não pode ser diminuido o estoque posi está com 0.");
    //         return $this->redirectToRoute("app_produtos");
    //     }

    //     $vendasRepository->inserir("O produto {$nome} teve uma venda e foi diminuido 1 do estoque.");

    //     $this->addFlash("success","O produto {$nome} foi diminuido 1 do estoque.");
    //     return $this->redirectToRoute("app_produtos");
    // }
            

    #[Route('produtos/quantidade/aumentar/{id}', name: 'app_AumentarQuantidadeProdutos')]
    public function aumentarQuantidade($id, ProdutosRepository $produtosRepository, VendasRepository $vendasRepository): Response
    {
        $produtosRepository->aumentarEstoque($id);
        $nome = $produtosRepository->find($id)->getNome();

        $vendasRepository->inserir("O produto {$nome} foi aumentado 1 no estoque.");

        $this->addFlash("success","O produto {$nome} foi aumentado 1 do estoque.");
        return $this->redirectToRoute("app_produtos");
    }


    #[Route('produtos/vendas', name: 'app_Vendas')]
    public function vendas(VendasRepository $vendasRepository): Response
    {
        $vendas = $vendasRepository->getVendas();
        return $this->render("vendas/vendas.html.twig",[
            "vendas" => $vendas
        ]);
    }


    #[Route('produtos/excluir/{id}/{nome}', name: "app_ExcluirProduto")]
    public function excuirProduto($id,$nome, ProdutosRepository $produtosRepository): Response
    {
        $excluir = $produtosRepository->excluirProduto($id);
        
        if(!$excluir){
            $this->addFlash('danger',"o Produto de id {$id} não existe!");
            return $this->redirectToRoute("app_produtos");
        }

        $this->addFlash('success',"O produto {$nome} foi excluido com sucesso!");
        return $this->redirectToRoute("app_produtos");
    }


    #[Route(path: '/produtos/editar/{id}', name: 'app_editarProduto')]
    public function editarProduto($id, ProdutosRepository $produtosRepository, CategoriasRepository $categoriasRepository): Response
    {
        $produto = $produtosRepository->find($id);

        if($produto == NULL){
            $this->addFlash('danger', "Produto Inexistente.");
            return $this->redirectToRoute("app_produtos");
        }

        $categorias = $categoriasRepository->findAll();
 
        return $this->render('produtos/addProduto.html.twig',[
            "categorias" => $categorias,
            "modo" => "editar",
            "produto" => $produto,
            "id" => $id
        ]);
    }

    
    #[Route(path: '/produtos/registrar/editar', name: 'app_editarProdutoRegistrar')]
    public function registrarEditarCategoria(ProdutosRepository $produtosRepository, Request $request): Response
    {

        $token = $request->request->get("_csrf_token");

        if(!$this->isCsrfTokenValid("addProduto",$token)){
            $this->addFlash("danger","Token CRSF inválido!");
            return $this->redirectToRoute("app_produtos");
        }

        $id = $request->request->get("id");

        $dados = [
            "nome" => $request->request->get("nome"),
            "descricao" => $request->request->get("descricao"),
            "categoria" => $request->request->get("categoria"),
            "quantidade" => $request->request->get("quantidade"),
            "valor" => $request->request->get("valor")
        ];

        $editar = $produtosRepository->editarProduto($id,$dados);

        if($editar){
            $this->addFlash('success', "produto {$dados['nome']} Editado com sucesso.");
            return $this->redirectToRoute("app_produtos");
        }else{
            $this->addFlash('danger', "Ocorreu um erro ao editar Produto.");
            return $this->redirectToRoute("app_produtos");   
        }
    }
}
