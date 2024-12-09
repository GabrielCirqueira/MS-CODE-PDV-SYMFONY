<?php

namespace App\Controller\Carrinho;

use App\Repository\CarrinhoRepository;
use App\Repository\ItemRepository;
use App\Service\VerificarPermissaoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CarrinhoController extends AbstractController
{
    private $verificarPermissaoService;

    public function __construct(VerificarPermissaoService $verificarPermissaoService)
    {
        $this->verificarPermissaoService = $verificarPermissaoService;
    }


    #[Route(path: '/carrinho', name: 'carrinho')]
    public function carrinho(): Response
    {
        if (!$this->verificarPermissaoService->execute('nova-venda')) {
            return $this->render('Login/error.html.twig');
        }
        return $this->render('carrinho/carrinho.html.twig');
    }

    #[Route(path: '/carrinho/{id}/aguardando', name: 'carrinhoAguardandoPagamento')]
    public function aguardando($id,
    CarrinhoRepository $carrinhoRepository,
    ItemRepository $itemRepository,
    Security $security): Response
    {
        if (!$this->verificarPermissaoService->execute('nova-venda')) {
            return $this->render('Login/error.html.twig');
        }
        $carrinho = $carrinhoRepository->find($id);
        $produtos = $itemRepository->buscar($carrinho);
        return $this->render("carrinho/carrinhoAguardando.html.twig", [
            "carrinho" => $carrinho,
            "produtos" => $produtos
        ]);
    }

    #[Route(path: '/carrinho/listar', name: 'carrinhosListar')]
    public function listar(
    CarrinhoRepository $carrinhoRepository,
    ItemRepository $itemRepository,
    Security $security): Response
    {
        if (!$this->verificarPermissaoService->execute('listar-vendas')) {
            return $this->render('Login/error.html.twig');
        }
        $carrinhos = $carrinhoRepository->findAll();
        return $this->render("carrinho/CarrinhosListar.html.twig", [
            "carrinhos" => $carrinhos,
        ]);
    }

}