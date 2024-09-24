<?php

namespace App\Controller\Categorias;

use App\Repository\CategoriasRepository;
use App\Service\CategoriaService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\VarDumper\VarDumper;

class CategoriasController extends AbstractController
{
    
    #[Route(path: '/categorias', name: 'app_categorias')]
    public function index(CategoriasRepository $categoriasRepository): Response
    {
        $categorias = $categoriasRepository->findAll();
        return $this->render('categorias/categorias.html.twig',[
            "categorias" => $categorias
        ]);
    }

    #[Route(path: '/categorias/adicionar', name: 'app_addCategoria')]
    public function adicionarCategoria(): Response
    {
        // $this->addFlash('success', 'Login realizado com sucesso!');
        return $this->render('categorias/addCategoria.html.twig');
    }

    #[Route(path: '/categorias/adicionar/registrar', name: 'app_RegistrarCategoria')]
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

    #[Route('/categorias/excluir/{id}/{nome}', 'app_excluirCategoria')]
    public function excluirCategoria($id,$nome, CategoriasRepository $categoriasRepository): Response
    {
        $excluir = $categoriasRepository->excluirCategoria($id);
        
        if(!$excluir){
            $this->addFlash('danger',"A categoria de id {$id} não existe!");
            return $this->redirectToRoute("app_categorias");
        }

        $this->addFlash('success',"A categoria {$nome} foi excluida com sucesso!");
        return $this->redirectToRoute("app_categorias");
    }
}
