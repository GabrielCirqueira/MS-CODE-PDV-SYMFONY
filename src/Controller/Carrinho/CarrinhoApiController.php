<?php

namespace App\Controller\Carrinho;

use App\Entity\Carrinho;
use App\Repository\CarrinhoRepository;
use App\Repository\ClienteRepository;
use App\Repository\ItemRepository;
use App\Service\AdicionarProdutosCarrinhoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarrinhoApiController extends AbstractController
{

    #[Route('/api/carrinho/{idCliente}', name: 'carrinhoApi')]
    public function carrinho(
        $idCliente,
        ClienteRepository $clienteRepository,
        CarrinhoRepository $carrinhoRepository,
        ItemRepository $itemRepository,
        Security $security
    ): JsonResponse {
        $cliente = $clienteRepository->find($idCliente);
        $carrinhoBusca = $carrinhoRepository->buscar($cliente);
        if ($carrinhoBusca == null) {

            $usuario = $security->getUser();
            $carrinho = new Carrinho($cliente, $usuario);
            $carrinhoRepository->salvar($carrinho);

            return new JsonResponse([
                'carrinho' => $carrinho->getId(),
                'dadosCarrinho' => [
                    'status' => 'Pendente',
                    'data' => $carrinho->getCriadoEm()->format(format: "d-m-Y"),
                    'valor' => 0,
                    'itens' => null,
                ],
                'mensagem' => 'Carrinho criado com sucesso!',
            ], Response::HTTP_OK);
        }

        $carrinho = $carrinhoRepository->find($carrinhoBusca);
        $itens = $itemRepository->buscar($carrinho);

        return new JsonResponse([
            'carrinho' => $carrinhoBusca,
            'dadosCarrinho' => [
                'status' => $carrinho->getStatus(),
                'data' => $carrinho->getAtualizadoEm()
                ? $carrinho->getAtualizadoEm()->format("d-m-Y")
                : ($carrinho->getCriadoEm() ? $carrinho->getCriadoEm()->format("d-m-Y") : null),
                'valor' => $carrinho->getValorTotal() ?? 0,
                'itens' => $itens,
            ],
            'status' => 'Aguardando Pagamento',
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
        $produtos = $data["produtos"];

        try {
            $APCservice->execute(idCarrinho: $idCarrinho, produtos: $produtos);

            return $this->json(['mensagem' => 'Produtos adicionados com sucesso!'], Response::HTTP_OK, ['Content-Type' => 'application/json']);
        } catch (\Exception $e) {
            return $this->json([
                'erro' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/api/carrinho/{id}/finalizar', name: 'finalizarCarrinho')]
    public function finalizarCarrinho($id, CarrinhoRepository $carrinhoRepository): Response
    {
        $carrinho = $carrinhoRepository->find($id);
        $carrinho->setStatus(Carrinho::STATUS_FINALIZADO);
        $carrinho->setFinalizadoEm();
        $carrinhoRepository->salvar($carrinho);
        return $this->json([
            'mensagem' => 'Carrinho finalizado com sucesso!',
            'carrinho' => [
                "status" => $carrinho->getStatus(),
                "dataFinalizado" => $carrinho->getFinalizadoEm()->format("d/m/Y"),
            ],

        ], Response::HTTP_OK);
    }

    #[Route('/api/carrinho/{id}/cancelar', name: 'cancelarCarrinho')]
    public function cancelarCarrinho($id, CarrinhoRepository $carrinhoRepository): Response
    {
        $carrinho = $carrinhoRepository->find($id);
        $carrinho->setStatus(Carrinho::STATUS_CANCELADO);
        $carrinho->setFinalizadoEm();
        $carrinhoRepository->salvar($carrinho);
        return $this->json([
            'mensagem' => 'Carrinho cancelado com sucesso!',
            'carrinho' => [
                "status" => $carrinho->getStatus(),
                "dataFinalizado" => $carrinho->getFinalizadoEm()->format("d/m/Y"),
            ],

        ], Response::HTTP_OK);
    }

}
