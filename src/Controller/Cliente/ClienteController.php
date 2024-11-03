<?php

namespace App\Controller\Cliente;

use App\Service\ClienteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClienteController extends AbstractController
{
    private $clienteService;

    public function __construct(ClienteService $clienteService)
    {
        $this->clienteService = $clienteService;
    }

    #[Route("/clientes", name: "clientes", methods: ['GET'])]
    public function clientes(): Response
    {
        $clientes = $this->clienteService->listarTodosClientes();
        return $this->render("cliente/clientes.html.twig", ["clientes" => $clientes]);
    }

    #[Route("/clientes/adicionar", name: "adicionarCliente", methods: ['GET'])]
    public function adicionarCliente(): Response
    {
        return $this->render("cliente/addCliente.html.twig", ["modo" => "adicionar"]);
    }

    #[Route(path: '/clientes/editar/{id}', name: 'editarCliente', methods: ["GET"])]
    public function editarCliente($id): Response
    {
        $cliente = $this->clienteService->buscarClientePorId($id);

        if ($cliente == NULL) {
            $this->addFlash('danger', "Cliente Inexistente.");
            return $this->redirectToRoute("clientes");
        } 
 
        return $this->render('cliente/addCliente.html.twig', [
            "cliente" => $cliente,
            "modo" => "editar",
            "id" => $id
        ]);
    }
}
