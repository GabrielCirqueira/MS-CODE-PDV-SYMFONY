<?php

namespace App\Controller\Produtos;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProdutosController extends AbstractController
{
    #[Route('/produtos', name: 'app_produtos')]
    public function index(): Response
    {
        return $this->render('produtos/produtos.html.twig');
    }

    #[Route('/produtos/adicionar', name: 'app_adicionarProdutos')]
    public function adicionarProduto(): Response
    {
        return $this->render('produtos/addProduto.html.twig');
    }

}
