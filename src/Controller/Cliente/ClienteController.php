<?php

namespace App\Controller\Cliente;

use App\Entity\Cliente;
use App\Repository\ClienteRepository;
use PhpParser\Builder\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ClienteController extends AbstractController
{
    #[Route('/api/clientes', name: 'app_getClientes', methods: ['GET'])]
    public function getClientes(ClienteRepository $clienteRepository, SerializerInterface $serializer): JsonResponse
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
    #[Route("/clientes", name: "app_clientes", methods: ['GET'])]
    public function clientes(ClienteRepository $clienteRepository): Response
    {
        $clientes = $clienteRepository->findAll();
        return $this->render("cliente/clientes.html.twig",["clientes" => $clientes]);
    }

    #[Route("/clientes/adicionar", name: "app_adicionarCliente", methods: ['GET'])]
    public function adicionarCliente(): Response
    {
        return $this->render("cliente/addCliente.html.twig",["modo" => "adicionar"]);
    }
    
    #[Route("/clientes/adicionar", name: "app_RegistrarCliente", methods: ['POST'])]
    public function registrarCliente(Request $request, ClienteRepository $clienteRepository): Response
    {
        $token = $request->request->get("_csrf_token");

        if(!$this->isCsrfTokenValid("addCliente",$token)){
            $this->addFlash("danger","Token CRSF inválido!");
            return $this->redirectToRoute("app_clientes");
        }
        
        $cliente = new Cliente();
        $cliente->setCpf((int) $request->request->get("cpf"));
        $cliente->setNome((string) $request->request->get("nome"));

        $clienteRepository->Adicionar($cliente);

        $this->addFlash("success","Cliente Adicionado com sucesso!");
        return $this->redirectToRoute("app_adicionarCliente");
    }

    #[Route('clientes/excluir/{id}/{nome}', name: "app_excluirCliente")]
    public function excuirProduto($id,$nome, ClienteRepository $clienteRepository): Response
    {
        $excluir = $clienteRepository->excluir($id);
        
        if(!$excluir){
            $this->addFlash('danger',"o Cliente de id {$id} não existe!");
            return $this->redirectToRoute("app_clientes");
        }

        $this->addFlash('success',"O Cliente {$nome} foi excluido com sucesso!");
        return $this->redirectToRoute("app_clientes");
    }

    #[Route(path: '/clientes/editar/{id}', name: 'app_editarCliente', methods: ["GET"])]
    public function editarCliente($id, ClienteRepository $clienteRepository): Response
    {
        $cliente = $clienteRepository->find($id);

        if($cliente == NULL){
            $this->addFlash('danger', "Cliente Inexistente.");
            return $this->redirectToRoute("app_clientes");
        } 
 
        return $this->render('cliente/addCliente.html.twig',[
            "cliente" => $cliente,
            "modo" => "editar",
            "id" => $id
        ]);
    }

    #[Route(path: '/clientes/editar', name: 'app_editarClienteRegistrar', methods: ["POST"])]
    public function editarClienteRegistrar(ClienteRepository $clienteRepository, Request $request): Response
    {
        $token = $request->request->get("_csrf_token");

        if(!$this->isCsrfTokenValid("editarCliente",$token)){
            $this->addFlash("danger","Token CRSF inválido!");
            return $this->redirectToRoute("app_clientes");
        }

        $id = $request->request->get("id");

        $dados = [
            "nome" => $request->request->get("nome"),
            "cpf" => $request->request->get("cpf"),
        ];

        $editar = $clienteRepository->editar($id,$dados);

        if($editar){
            $this->addFlash('success', "Cliente {$dados['nome']} Editado com sucesso.");
            return $this->redirectToRoute("app_clientes");
        }else{
            $this->addFlash('danger', "Ocorreu um erro ao editar o Cliente.");
            return $this->redirectToRoute("app_clientes");
        }
    }


}
