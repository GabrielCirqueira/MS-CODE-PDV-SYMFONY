<?php

namespace App\Controller\Categorias;

use App\Repository\CategoriasRepository;
use App\Service\CategoriaService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoriasController extends AbstractController
{
    
    #[Route('/categorias', name: 'app_categorias')]
    public function index(CategoriasRepository $categoriasRepository): Response
    {
        $categorias = $categoriasRepository->findAll();
        return $this->render('categorias/categorias.html.twig',[
            "categorias" => $categorias
        ]);
    }


    #[Route('/categorias/adicionar', name: 'app_addCategoria')]
    public function adicionarCategoria(): Response
    {
        // $this->addFlash('success', 'Login realizado com sucesso!');
        return $this->render('categorias/addCategoria.html.twig');
    }

    #[Route('/categorias/adicionar/registrar', name: 'app_RegistrarCategoria')]
    public function registrarCategoria(CategoriaService $categoriaService,Request $request): Response
    {
        $nome = $request->request->get("nome");
        $inserir = $categoriaService->registarCategoria($nome);

        if(!$inserir){
            $this->addFlash('danger', "A categoria {$nome} já existe.");
            return $this->redirectToRoute("app_addCategoria");
        }

        $this->addFlash('success', "A categoria {$nome} foi Adicionada com Sucesso!");
        return $this->redirectToRoute("app_addCategoria");

    }
}
