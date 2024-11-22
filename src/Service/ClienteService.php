<?php

namespace App\Service;

use App\Entity\Cliente;
use App\Repository\ClienteRepository;

class ClienteService
{
    private $clienteRepository;

    public function __construct(ClienteRepository $clienteRepository)
    {
        $this->clienteRepository = $clienteRepository;
    }

    public function listarTodosClientes()
    {
        return $this->clienteRepository->findAll();
    }

    public function adicionarCliente(Cliente $cliente)
    {
        return $this->clienteRepository->adicionar($cliente);
    }

    public function excluirCliente(int $id)
    {
        return $this->clienteRepository->excluir($id);
    }

    public function editarCliente(int $id, string $nome)
    {
        return $this->clienteRepository->editar($id, $nome);
    }

    public function buscarClientePorId(int $id): ?Cliente
    {
        return $this->clienteRepository->find($id);
    }
}
