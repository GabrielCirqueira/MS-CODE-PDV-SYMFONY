<?php

namespace App\Repository;

use App\Entity\Cliente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cliente>
 */
class ClienteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cliente::class);
    }

    public function Adicionar(Cliente $cliente): void
    {
        $this->getEntityManager()->persist($cliente);
        $this->getEntityManager()->flush();
    }

    public function editar($id,$dados): bool
    {
        $cliente = $this->find($id);

        $cliente->setNome($dados["nome"]);
        $cliente->setCpf($dados["cpf"]);

        $this->getEntityManager()->persist($cliente);
        $this->getEntityManager()->flush();

        return True;
    }

    public function buscarClienteCPF($cpf) : cliente | bool
    {
        $query = $this->createQueryBuilder("cliente")
        ->andWhere("cliente.cpf = :cpf")
        ->setParameter("cpf",$cpf)
        ->getQuery();

        $cliente = $query->getOneOrNullResult();

        return $cliente ? $cliente : False;
    }

}
