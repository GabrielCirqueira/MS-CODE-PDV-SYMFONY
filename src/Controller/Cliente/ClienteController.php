<?php

namespace App\Controller\Cliente;

use App\Service\ClienteService;
use App\Service\VerificarPermissaoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ClienteController extends AbstractController
{
    private $verificarPermissaoService;
    private ClienteService $clienteService;

    public function __construct(ClienteService $clienteService,
    VerificarPermissaoService $verificarPermissaoService)
    {
        $this->verificarPermissaoService = $verificarPermissaoService;
        $this->clienteService = $clienteService;
    }

    #[Route("/clientes", name: "clientes", methods: ['GET'])]
    public function clientes(Security $security): Response
    {
        if (!$this->verificarPermissaoService->execute('ver-clientes')) {
            return $this->render('Login/error.html.twig');
        }

        $clientes = $this->clienteService->listarTodosClientes();
        return $this->render("cliente/clientes.html.twig", ["clientes" => $clientes]);
    }

    #[Route("/clientes/adicionar", name: "adicionarCliente", methods: ['GET'])]
    public function adicionarCliente(Security $security): Response
    {
        if (!$this->verificarPermissaoService->execute('adicionar-cliente')) {
            return $this->render('Login/error.html.twig');
        }

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
