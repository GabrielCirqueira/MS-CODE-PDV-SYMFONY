<?php

namespace App\Controller\Categorias;

use App\Repository\CategoriaRepository;
use App\Service\CategoriaService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoriasOperarController extends AbstractController
{
    #[Route(path: '/categorias/adicionar/registrar', name: 'RegistrarCategoria')]
    public function registrarCategoria(CategoriaService $categoriaService, Request $request): Response
    {
        $nome = $request->request->get("nome");
        $inserir = $categoriaService->registarCategoria($nome);

        if (!$inserir) {
            $this->addFlash('danger', "A categoria {$nome} já existe.");
            return $this->redirectToRoute("addCategoria");
        }

        $this->addFlash('success', "A categoria {$nome} foi adicionada com sucesso!");
        return $this->redirectToRoute("addCategoria");
    }

    #[Route(path: '/categorias/registrar/editar', name: 'editarCategoriaRegistrar')]
    public function registrarEditarCategoria(CategoriaRepository $categoriasRepository, Request $request): Response
    {
        $id = $request->request->get("id");
        $nome = $request->request->get("nome");

        $editar = $categoriasRepository->editarCategoria($id, $nome);

        if ($editar) {
            $this->addFlash('success', "Categoria editada com sucesso.");
            return $this->redirectToRoute("categorias");
        } else {
            $this->addFlash('danger', "Ocorreu um erro ao editar categoria.");
            return $this->redirectToRoute("categorias");
        }
    }

    #[Route('/categorias/excluir/{id}/{nome}', 'excluirCategoria')]
    public function excluirCategoria($id, $nome, CategoriaRepository $categoriasRepository): Response
    {
        $excluir = $categoriasRepository->excluirCategoria($id);
        
        if (!$excluir) {
            $this->addFlash('danger', "A categoria de id {$id} não existe!");
            return $this->redirectToRoute("categorias");
        }

        $this->addFlash('success', "A categoria {$nome} foi excluída com sucesso!");
        return $this->redirectToRoute("categorias");
    }
}
