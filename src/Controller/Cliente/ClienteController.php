<?php

namespace App\Controller\Cliente;

use App\Entity\Cliente;
use App\Repository\ClienteRepository;
use PhpParser\Builder\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route("/clientes/adicionar", name: "app_adicionarCliente", methods: ['GET'])]
    public function adicionarCliente(): Response
    {
        return $this->render("cliente/addCliente.html.twig",["modo" => "adicionar"]);
    }
    
    #[Route("/clientes/adicionar", name: "app_RegistrarCliente", methods: ['POST'])]
    public function registrarCliente(Request $request, ClienteRepository $clienteRepository): Response
    {
        $cliente = new Cliente();
        $cliente->setCpf((int) $request->query->get("cpf"));
        $cliente->setNome((string) $request->query->get("nome"));

        $clienteRepository->Adicionar($cliente);

        $this->addFlash("success","Cliente Adicionado com sucesso!");
        return $this->redirectToRoute("app_adicionarCliente");
    }
}
