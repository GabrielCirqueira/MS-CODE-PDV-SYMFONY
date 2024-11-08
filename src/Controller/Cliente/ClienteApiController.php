<?php

namespace App\Controller\Cliente;

use App\Service\ClienteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ClienteApiController extends AbstractController
{
    private $clienteService;

    public function __construct(ClienteService $clienteService)
    {
        $this->clienteService = $clienteService;
    }

    #[Route('/api/clientes', name: 'getClientes', methods: ['GET'])]
    public function getClientes(SerializerInterface $serializer): JsonResponse
    {
        try {
            $clientes = $this->clienteService->listarTodosClientes();
            $json = $serializer->serialize($clientes, 'json', ['groups' => 'cliente']);
            return new JsonResponse($json, Response::HTTP_OK, [], true);
        } catch (\Exception $e) {
            return new JsonResponse(
                ['error' => 'Ocorreu um erro ao buscar clientes.'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
