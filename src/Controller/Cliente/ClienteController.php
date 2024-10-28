<?php

namespace App\Controller\Cliente;

use App\Repository\ClienteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ClienteController extends AbstractController
{
    #[Route('/api/clientes', name: 'app_getClientes', methods: ['GET'])]
    public function clientes(ClienteRepository $clienteRepository, SerializerInterface $serializer): JsonResponse
    {
        try {
            $clientes = $clienteRepository->findAll();
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
