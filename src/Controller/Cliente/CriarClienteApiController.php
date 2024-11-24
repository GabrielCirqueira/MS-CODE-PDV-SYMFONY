<?php

namespace App\Controller\Cliente;

use App\Entity\Carrinho;
use App\Repository\ClienteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\ItemRepository;
use App\Repository\CarrinhoRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

class CriarClienteApiController extends AbstractController
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
        $carrinhoBusca = $carrinhoRepository->findOneBy(['cliente' => $cliente]);
        if ($carrinhoBusca == null) {

            $usuario = $security->getUser();
            $carrinho = new Carrinho($cliente, $usuario);
            $carrinhoRepository->salvarCarrinho($carrinho);

            return new JsonResponse([
                'status' => 'criado',
                'mensagem' => 'Carrinho criado com sucesso!',
            ], Response::HTTP_OK); 
        }

        return new JsonResponse([
            'status' => 'existente',
            'mensagem' => 'Carrinho já existe.',
        ], Response::HTTP_OK);
    }
}
