<?php

namespace App\Controller\Carrinho;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CarrinhoController extends AbstractController
{
    #[Route(path: '/carrinho', name: 'carrinho')]
    public function adicionarCategoria(): Response
    {
        return $this->render('carrinho/carrinho.html.twig');
    }
}