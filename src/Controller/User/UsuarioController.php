<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Repository\PermissaoRepository;
use App\Repository\UserRepository;
use App\Service\VerificarPermissaoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UsuarioController extends AbstractController
{
    private $verificarPermissaoService;

    public function __construct(VerificarPermissaoService $verificarPermissaoService)
    {
        $this->verificarPermissaoService = $verificarPermissaoService;
    }

    #[Route('/usuarios', name: 'usuariosListar')]
    public function listar(UserRepository $userRepository): Response
    {

        if (!$this->verificarPermissaoService->execute('ver-usuarios')) {
            return $this->render('Login/error.html.twig');
        }
    
        return $this->render("usuarios/usuarios.html.twig",[
            "usuarios" => $userRepository->findAll()
        ]);
    }


    #[Route('/usuarios/adicionar', name: 'adicionarUsuario')]
    public function adicionar(PermissaoRepository $permissaoRepository): Response
    {

        if (!$this->verificarPermissaoService->execute('criar-usuario')) {
            return $this->render('Login/error.html.twig');
        }

        $permissoes = $permissaoRepository->findAll();
        $permissoesAgrupadas = [];
    
        foreach ($permissoes as $permissao) {
            $grupo = $permissao->getGrupo();
            if (!isset($permissoesAgrupadas[$grupo])) {
                $permissoesAgrupadas[$grupo] = [];
            }
            $permissoesAgrupadas[$grupo][] = $permissao;
        }
    
        return $this->render("usuarios/addUser.html.twig", [
            'permissoesAgrupadas' => $permissoesAgrupadas,
            'modo' => "adicionar"
        ]);
    }

    #[Route('/usuarios/registrar', name: 'registrarUsuario', methods: ['POST'])]
    public function registrarUsuario(
        Request $request,
        PermissaoRepository $permissaoRepository,
        UserRepository $userRepository): Response
    {
        $nome = $request->request->get('nome');
        $email = $request->request->get('email');
        $senha = $request->get('senha');
        $permissoesIds = $_REQUEST["permissoes"];

        if (empty($nome) || empty($email) || empty($senha)) {
            $this->addFlash('danger', 'Todos os campos são obrigatórios.');
            return $this->redirectToRoute('adicionarUsuario');
        }

        $user = new User();
        $user->setNome($nome);
        $user->setEmail($email);
        $user->setAtivo(True);
        $user->setPassword(password_hash($senha, PASSWORD_BCRYPT));

        foreach ($permissoesIds as $id) {
            $permissao = $permissaoRepository->find($id);
            if ($permissao) {
                $user->addPermissao($permissao);
            }
        }

        $userRepository->salvar($user);

        $this->addFlash('success', 'Usuário cadastrado com sucesso.');
        return $this->redirectToRoute('adicionarUsuario');
    }

    #[Route(path: '/usuarios/inativar/{id}', name: 'inativarUsuario')]
    public function inativar($id, UserRepository $userRepository): Response
    {
        $usuario = $userRepository->find($id);

        if (!$usuario) {
            $this->addFlash('error', "Usuário não encontrado.");
            return $this->redirectToRoute("usuariosListar");
        }

        $usuario->setAtivo(false);
        $userRepository->salvar($usuario);

        $this->addFlash('success', "Usuário {$usuario->getNome()} foi inativado com sucesso.");
        return $this->redirectToRoute("usuariosListar");
    }

    #[Route(path: '/usuarios/ativar/{id}', name: 'ativarUsuario')]
    public function ativar($id, UserRepository $userRepository): Response
    {
        $usuario = $userRepository->find($id);

        if (!$usuario) {
            $this->addFlash('error', "Usuário não encontrado.");
            return $this->redirectToRoute("usuariosListar");
        }

        $usuario->setAtivo(true);
        $userRepository->salvar($usuario);

        $this->addFlash('success', "Usuário {$usuario->getNome()} foi ativado com sucesso.");
        return $this->redirectToRoute("usuariosListar");
    }

    #[Route(path: '/usuarios/editar/{id}', name: 'editarUsuario')]
    public function editar($id, UserRepository $userRepository, PermissaoRepository $permissaoRepository): Response
    {
        if ($this->verificarPermissaoService->execute('editar-usuario')) {
            return $this->render('Login/error.html.twig');
        }

        $permissoes = $permissaoRepository->findAll();
        $permissoesAgrupadas = [];
    
        foreach ($permissoes as $permissao) {
            $grupo = $permissao->getGrupo();
            if (!isset($permissoesAgrupadas[$grupo])) {
                $permissoesAgrupadas[$grupo] = [];
            }
            $permissoesAgrupadas[$grupo][] = $permissao;
        }

        $usuario = $userRepository->find($id);
        return $this->render("usuarios/editar.html.twig",[
            'permissoesAgrupadas' => $permissoesAgrupadas,
            "usuario" => $usuario
        ]);
    }

    #[Route('/usuarios/atualizar/{id}', name: 'atualizarUsuario', methods: ['POST'])]
    public function atualizarUsuario(
        $id,
        Request $request,
        PermissaoRepository $permissaoRepository,
        UserRepository $userRepository
    ): Response {
        $nome = $request->request->get('nome');
        $senha = $request->request->get('senha');
        $permissoesIds = $_REQUEST["permissoes"];

        $usuario = $userRepository->find($id);

        if (!$usuario) {
            $this->addFlash('danger', 'Usuário não encontrado.');
            return $this->redirectToRoute('listarUsuarios');
        }

        if (empty($nome)) {
            $this->addFlash('danger', 'O campo nome é obrigatório.');
            return $this->redirectToRoute('editarUsuario', ['id' => $id]);
        }

        $usuario->setNome($nome);

        if (!empty($senha)) {
            $usuario->setPassword(password_hash($senha, PASSWORD_BCRYPT));
        }

        foreach ($usuario->getPermissoes() as $permissao) {
            $usuario->removePermissao($permissao);
        }

        foreach ($permissoesIds as $idPermissao) {
            $permissao = $permissaoRepository->find($idPermissao);
            if ($permissao) {
                $usuario->addPermissao($permissao);
            }
        }

        $userRepository->salvar($usuario);

        $this->addFlash('success', 'Usuário atualizado com sucesso.');
        return $this->redirectToRoute('usuariosListar');
    }



}