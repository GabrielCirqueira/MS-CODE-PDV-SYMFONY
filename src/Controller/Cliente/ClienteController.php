<?php

namespace App\Controller\Cliente;

use App\Repository\ClienteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ClienteController extends AbstractController
{
    #[Route('/api/clientes', name: 'app_getClientes', methods: "GET")]
    public function clientes(ClienteRepository $clienteRepository, SerializerInterface $serializer): Response
    {
        $clietes = $clienteRepository->findAll();
        $json = $serializer->serialize($clietes,'json',['groups' => "cliente"]);
        return new Response($json, Response::HTTP_OK,["Content-Type" => "application/json"]);
    }
}
