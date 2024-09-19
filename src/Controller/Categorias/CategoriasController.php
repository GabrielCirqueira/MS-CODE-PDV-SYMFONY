<?php

namespace App\Controller\Categorias;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoriasController extends AbstractController
{
    
    #[Route('/categorias', name: 'app_categorias')]
    public function index(): Response
    {
        return $this->render('categorias/categorias.html.twig');
    }


    #[Route('/categorias/adicionar', name: 'app_addCategoria')]
    public function adicionarCategoria(): Response
    {
        return $this->render('categorias/addCategoria.html.twig');
    }

    #[Route('/categorias/adicionar/registrar', name: 'app_RegistrarCategoria')]
    public function registrarCategoria(): Response
    {
        return new Response;
    }
}
