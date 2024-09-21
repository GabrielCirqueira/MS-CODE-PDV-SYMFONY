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
#[Route('/register', name: 'app_register')]
    public function register(Request $request, UserService $userService): Response
    {
        if ($request->isMethod('POST')) {
            $dados = [
                "email" => $request->request->get("email"),
                "nome"  => $request->request->get("nome"),
                "senha" => $request->request->get("senha")
            ];

            $token = $request->request->get("_csrf_token");

            if ($this->isCsrfTokenValid("registrar", $token)) {
                $userService->registarUsuario($dados);

                return $this->redirectToRoute("app_login");
            }
        }

        return $this->render('Login/register.html.twig');
    }
}
