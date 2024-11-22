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

    public function editar($id,$nome): bool
    {
        $cliente = $this->find($id);

        $cliente->setNome($nome);

        $this->getEntityManager()->persist($cliente);
        $this->getEntityManager()->flush();

        return True;
    }

    public function buscarPorCpf($cpf): ?Cliente
    {
        return $this->createQueryBuilder("cliente")
            ->andWhere("cliente.cpf = :cpf")
            ->setParameter("cpf", $cpf)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
