<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        CsrfTokenManagerInterface $csrfTokenManager,
        UserRepository $repositorio): Response
    {
     
        if($request->isMethod('POST')){
            $email = $request->request->get("email");
            $nome = $request->request->get("nome");
            $senha = is_string($request->request->get("senha"));
            $token = $request->request->get("_csrf_token");

            if($this->isCsrfTokenValid("registrar",$token)){
                $usuario = new User;
                $usuario->setEmail($email);
                $usuario->setNome($nome);
                $usuario->setPassword($userPasswordHasher->hashPassword($usuario,$senha));

                $repositorio->salvarUsuario($usuario);

                return $this->redirectToRoute("app_login");
                
            }
        }
        return $this->render('registration/register.html.twig');
    }
}
