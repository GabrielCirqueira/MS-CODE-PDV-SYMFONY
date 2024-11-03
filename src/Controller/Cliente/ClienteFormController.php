<?php

namespace App\Controller\Cliente;

use App\Entity\Cliente;
use App\Service\ClienteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClienteFormController extends AbstractController
{
    private $clienteService;

    public function __construct(ClienteService $clienteService)
    {
        $this->clienteService = $clienteService;
    }

    #[Route("/clientes/adicionar", name: "RegistrarCliente", methods: ['POST'])]
    public function registrarCliente(Request $request): Response
    {
        $token = $request->request->get("_csrf_token");

        if (!$this->isCsrfTokenValid("addCliente", $token)) {
            $this->addFlash("danger", "Token CRSF inválido!");
            return $this->redirectToRoute("clientes");
        }
        
        $cliente = new Cliente();
        $cliente->setCpf((int) $request->request->get("cpf"));
        $cliente->setNome((string) $request->request->get("nome"));

        $this->clienteService->adicionarCliente($cliente);

        $this->addFlash("success", "Cliente Adicionado com sucesso!");
        return $this->redirectToRoute("adicionarCliente");
    }

    #[Route('clientes/excluir/{id}/{nome}', name: "excluirCliente")]
    public function excluirCliente($id, $nome): Response
    {
        $excluir = $this->clienteService->excluirCliente($id);
        
        if (!$excluir) {
            $this->addFlash('danger', "O Cliente de id {$id} não existe!");
            return $this->redirectToRoute("clientes");
        }

        $this->addFlash('success', "O Cliente {$nome} foi excluido com sucesso!");
        return $this->redirectToRoute("clientes");
    }

    #[Route(path: '/clientes/editar', name: 'editarClienteRegistrar', methods: ["POST"])]
    public function editarClienteRegistrar(Request $request): Response
    {
        $token = $request->request->get("_csrf_token");

        if (!$this->isCsrfTokenValid("editarCliente", $token)) {
            $this->addFlash("danger", "Token CRSF inválido!");
            return $this->redirectToRoute("clientes");
        }

        $id = $request->request->get("id");

        $dados = [
            "nome" => $request->request->get("nome"),
            "cpf" => $request->request->get("cpf"),
        ];

        $editar = $this->clienteService->editarCliente($id, $dados);

        if ($editar) {
            $this->addFlash('success', "Cliente {$dados['nome']} Editado com sucesso.");
            return $this->redirectToRoute("clientes");
        } else {
            $this->addFlash('danger', "Ocorreu um erro ao editar o Cliente.");
            return $this->redirectToRoute("clientes");
        }
    }
}
