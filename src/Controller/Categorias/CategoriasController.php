<?php

namespace App\Controller\Categorias;

use App\Repository\CategoriaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoriasController extends AbstractController
{
    #[Route(path: '/categorias', name: 'categorias')]
    public function index(CategoriaRepository $categoriasRepository): Response
    {
        $categorias = $categoriasRepository->findAll();

        return $this->render('categorias/categorias.html.twig', [
            "categorias" => $categorias
        ]);
    }

    #[Route(path: '/categorias/adicionar', name: 'addCategoria')]
    public function adicionarCategoria(): Response
    {
        return $this->render('categorias/addCategoria.html.twig', [
            "modo" => "adicionar"
        ]);
    }

    #[Route(path: '/categorias/editar/{id}', name: 'editarCategoria')]
    public function editarCategoria($id, CategoriaRepository $categoriasRepository): Response
    {
        $categoria = $categoriasRepository->find($id);

        if ($categoria == null) {
            $this->addFlash('danger', "Categoria Inexistente.");
            return $this->redirectToRoute("categorias");
        }

        return $this->render('categorias/addCategoria.html.twig', [
            "modo" => "editar",
            "nome" => $categoria->getNome(),
            "id" => $id
        ]);
    }
}
