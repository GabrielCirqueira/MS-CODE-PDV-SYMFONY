<?php 

namespace App\Service;

use App\Entity\Categoria;
use App\Repository\CategoriaRepository;

class CategoriaService 
{
    private $CategoriaRepository;

    public function __construct(CategoriaRepository $CategoriaRepository)
    {
        $this->CategoriaRepository = $CategoriaRepository;
    }

    public function registarCategoria($nome): bool
    {
        $ConsultarCategoria = $this->CategoriaRepository->buscarCategoria($nome);

        if($ConsultarCategoria){
            return False;
        }

        $categoria = new Categoria();
        $categoria->setNome($nome); 
        
        $this->CategoriaRepository->salvarUsuario($categoria);

        return true;
    }
}
