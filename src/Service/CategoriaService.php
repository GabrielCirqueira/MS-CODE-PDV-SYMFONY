<?php 

namespace App\Service;

use App\Entity\Categorias;
use App\Entity\User;
use App\Repository\CategoriasRepository;
use App\Repository\UserRepository;

class CategoriaService 
{
    private $CategoriaRepository;

    public function __construct(CategoriasRepository $CategoriaRepository)
    {
        $this->CategoriaRepository = $CategoriaRepository;
    }

    public function registarCategoria($nome): bool
    {
        $ConsultarCategoria = $this->CategoriaRepository->buscarCategoria($nome);

        if($ConsultarCategoria){
            return False;
        }

        $categoria = new Categorias();
        $categoria->setNome($nome);
        
        $this->CategoriaRepository->salvarUsuario($categoria);

        return true;
    }
}
