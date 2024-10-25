<?php

namespace App\Controller\Categorias;

use App\Repository\CategoriaRepository;
use App\Service\CategoriaService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoriasController extends AbstractController
{
    
    #[Route(path: '/categorias', name: 'app_categorias')]
    public function index(CategoriaRepository $categoriasRepository): Response
    {
        $categorias = $categoriasRepository->findAll();

        return $this->render('categorias/categorias.html.twig',[
            "categorias" => $categorias
        ]);
    }

    #[Route(path: '/categorias/adicionar', name: 'app_addCategoria')]
    public function adicionarCategoria(): Response
    {
        return $this->render('categorias/addCategoria.html.twig',[
            "modo" => "adicionar"
        ]);
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

    #[Route(path: '/categorias/editar/{id}', name: 'app_editarCategoria')]
    public function editarCategoria($id, CategoriaRepository $categoriasRepository): Response
    {
        $categoria = $categoriasRepository->find($id);

        if($categoria == NULL){
            $this->addFlash('danger', "Categoria Inexistente.");
            return $this->redirectToRoute("app_categorias");
        }

        return $this->render('categorias/addCategoria.html.twig',[
            "modo" => "editar",
            "nome" => $categoria->getNome(),
            "id" => $id
        ]);
    }

    #[Route(path: '/categorias/registrar/editar', name: 'app_editarCategoriaRegistrar')]
    public function registrarEditarCategoria(CategoriaRepository $categoriasRepository, Request $request): Response
    {
        $id = $request->request->get("id");
        $nome = $request->request->get("nome");

        $editar = $categoriasRepository->editarCategoria($id,$nome);

        if($editar){
            $this->addFlash('success', "Categoria Editada com sucesso.");
            return $this->redirectToRoute("app_categorias");
        }else{
            $this->addFlash('danger', "Ocorreu um erro ao editar categoria.");
            return $this->redirectToRoute("app_categorias");   
        }
    }

    #[Route('/categorias/excluir/{id}/{nome}', 'app_excluirCategoria')]
    public function excluirCategoria($id,$nome, CategoriaRepository $categoriasRepository): Response
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
