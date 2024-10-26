<?php

namespace App\Repository;

use App\Entity\Categoria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Categoria>
 */
class CategoriaRepository extends ServiceEntityRepository
{
    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Categoria::class);
        $this->entityManager = $entityManager;
    }

    public function salvarUsuario(Categoria $categoria) : void
    {
        $this->getEntityManager()->persist($categoria);
        $this->getEntityManager()->flush();
    }

    public function buscarCategoria($nome) : Categoria | false
    {
        $query = $this->createQueryBuilder("categoria")
        ->andWhere("categoria.nome = :nome")
        ->setParameter("nome",$nome)
        ->getQuery();

        $categoria = $query->getOneOrNullResult();

        return $categoria ? $categoria : False;
    }

    public function excluirCategoria($id): bool
    {
        $categoria = $this->find($id);
        
        if($categoria == NULL){
            return false;
        }

        $this->entityManager->remove($categoria);
        $this->entityManager->flush();

        return True;
    }

    public function editarCategoria($id,$nome): bool
    {
        $categoria = $this->find($id);

        $categoria->setNome($nome);

        $this->entityManager->persist($categoria);
        $this->entityManager->flush();

        return True;
    }

}
