<?php

namespace App\Controller\Categorias;

use App\Repository\CategoriaRepository;
use App\Service\VerificarPermissaoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CategoriasController extends AbstractController
{
    private $verificarPermissaoService;

    public function __construct(VerificarPermissaoService $verificarPermissaoService)
    {
        $this->verificarPermissaoService = $verificarPermissaoService;
    }


    #[Route(path: '/categorias', name: 'categorias')]
    public function index(CategoriaRepository $categoriasRepository): Response
    {

        if (!$this->verificarPermissaoService->execute('Ver-Categorias')) {
            return $this->render('Login/error.html.twig');
        }

        $categorias = $categoriasRepository->findAll();

        return $this->render('categorias/categorias.html.twig', [
            "categorias" => $categorias
        ]);
    }

    #[Route(path: '/categorias/adicionar', name: 'addCategoria')]
    public function adicionarCategoria(): Response
    {
        if (!$this->verificarPermissaoService->execute('Adicionar-Categoria')) {
            return $this->render('Login/error.html.twig');
        }

        return $this->render('categorias/addCategoria.html.twig', [
            "modo" => "adicionar"
        ]);
    }

    #[Route(path: '/categorias/editar/{id}', name: 'editarCategoria')]
    public function editarCategoria($id, CategoriaRepository $categoriasRepository, Security $security): Response
    {
        if (!$this->verificarPermissaoService->execute('Ver-Categorias')) {
            return $this->render('Login/error.html.twig');
        }
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

