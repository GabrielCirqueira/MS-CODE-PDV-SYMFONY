<?php

namespace App\Controller\Cliente;

use App\Entity\Cliente;
use App\Repository\ClienteRepository;
use App\Service\ClienteService;
use App\Service\ValidarCpfService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClienteFormController extends AbstractController
{
    private ClienteService $clienteService;

    public function __construct(ClienteService $clienteService)
    {
        $this->clienteService = $clienteService;
    }

    #[Route("/clientes/adicionar", name: "RegistrarCliente", methods: ['POST'])]
    public function registrarCliente(Request $request,ValidarCpfService $validarCpfService, ClienteRepository $clienteRepository): Response
    {
        $token = $request->request->get("_csrf_token");

        if (!$this->isCsrfTokenValid("addCliente", $token)) {
            $this->addFlash("danger", "Token CRSF inválido!");
            return $this->redirectToRoute("clientes");
        }

        $cpf = preg_replace('/\D/', '', $request->request->get("cpf"));
        if(!$validarCpfService->execute($cpf)){
            $this->addFlash("danger", "CPF inválido!");
            return $this->redirectToRoute("adicionarCliente");
        }
        
        $cliente = new Cliente();

        if($clienteRepository->buscarClienteCPF($cpf)){
            $this->addFlash("danger", "CPF já cadastrado!");
            return $this->redirectToRoute("adicionarCliente");
        }

        $cliente->setCpf($cpf);

        if(is_numeric($request->request->get("nome"))){
            $this->addFlash("danger", "O nome inserido contém apenas números!");
            return $this->redirectToRoute("adicionarCliente"); 
        }
        $nome = trim((string) $request->request->get("nome"));
        $nome = preg_replace('/\s+/', ' ', $nome); 

        if(empty($nome)){
            $this->addFlash("danger", "O nome inserido está vazio!");
            return $this->redirectToRoute("adicionarCliente"); 
        }

        $cliente->setNome((string) $nome);

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
    public function editarClienteRegistrar(Request $request,ValidarCpfService $validarCpfService, ClienteRepository $clienteRepository): Response
    {
        $token = $request->request->get("_csrf_token");

        if (!$this->isCsrfTokenValid("editarCliente", $token)) {
            $this->addFlash("danger", "Token CRSF inválido!");
            return $this->redirectToRoute("clientes");
        }

        $id = $request->request->get("id");

        if(!$validarCpfService->execute($request->request->get("cpf"))){
            $this->addFlash("danger", "CPF inválido!");
            return $this->redirectToRoute("editarCliente",["id" => $id]);
        }

        $cpf = $request->request->get("cpf");
        $cpf = str_replace(['.', '-'], '', $cpf);

        $cliente = $clienteRepository->buscarClienteCPF($cpf);
        if($cliente){
            if(!($cliente->getId() == $id)){
                $this->addFlash("danger", "CPF já cadastrado!");
                return $this->redirectToRoute("editarCliente",["id" => $id]);
            }
        }

        if(is_numeric($request->request->get("nome"))){
            $this->addFlash("danger", "O nome inserido contém apenas números!");
            return $this->redirectToRoute("editarCliente",["id" => $id]);
        }

        $nome = trim((string) $request->request->get("nome"));
        $nome = preg_replace('/\s+/', ' ', $nome); 

        if(empty($nome)){
            $this->addFlash("danger", "O nome inserido está vazio!");
            return $this->redirectToRoute("editarCliente",["id" => $id]);
        }

        $dados = [
            "nome" => $nome,
            "cpf" => $cpf,
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
