<?php

namespace App\Controller\Carrinho;

use App\Entity\Carrinho;
use App\Repository\ClienteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CarrinhoRepository;
use App\Service\AdicionarProdutosCarrinhoService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CarrinhoApiController extends AbstractController
{

    #[Route('/api/carrinho/{idCliente}', name: 'carrinhoApi')]
    public function carrinho(
        $idCliente,
        ClienteRepository $clienteRepository,
        CarrinhoRepository $carrinhoRepository,
        Security $security
    ): JsonResponse
    {
        $cliente = $clienteRepository->find($idCliente);
        $carrinhoBusca = $carrinhoRepository->buscar($cliente);
        if ($carrinhoBusca == null) {

            $usuario = $security->getUser();
            $carrinho = new Carrinho($cliente, $usuario);
            $carrinhoRepository->salvar($carrinho);

            return new JsonResponse([
                'carrinho' => $carrinho->getId(),
                'status' => 'criado',
                'mensagem' => 'Carrinho criado com sucesso!',
            ], Response::HTTP_OK); 
        }

        return new JsonResponse([
            'carrinho' => $carrinhoBusca,
            'status' => 'existente',
            'mensagem' => 'Carrinho já existe.',
        ], Response::HTTP_OK);
    }

    #[Route('/api/carrinho/{idCarrinho}/adicionar/produtos', name: 'carrinhoInserirApi', methods: ['POST'])]
    public function inserirProdutosCarrinho(
        $idCarrinho,
        Request $request,
        AdicionarProdutosCarrinhoService $APCservice
    ): Response {
        $jsonData = $request->getContent();
        $data = json_decode($jsonData, true);

        try {

            $APCservice->execute(idCarrinho: $idCarrinho, dados: $data);
            return $this->json(['mensagem' => 'Produtos adicionados com sucesso!'], Response::HTTP_OK);

        } catch (\Exception $e) {
            return $this->json(['erro' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
    
}

