<?php

namespace App\Controller\Login;

use App\Entity\User;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController

{
#[Route('/register', name: 'register')]
    public function register(Request $request, UserService $userService): Response
    {
        if ($request->isMethod('POST')) {
            
            $dados = [
                "email" => (string) $request->request->get("email"),
                "nome"  => (string) $request->request->get("nome"),
                "senha" => (string) $request->request->get("senha"),
            ];

            $token = $request->request->get("_csrf_token");

            if ($this->isCsrfTokenValid("registrar", $token)) {
                $inserir = $userService->registarUsuario($dados);
                if($inserir){
                    return $this->redirectToRoute("login");
                }
                $error = "Este Email já está em uso!";
            }
        }

        return $this->render('Login/register.html.twig',[
            "error" => isset($error) ? $error : NULL
        ]);
    }
}
