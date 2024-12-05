<?php

namespace App\Controller\Login;

use App\Repository\UserRepository;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\MakerBundle\Security\Model\Authenticator;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginController extends AbstractController
{
    #[Route(path: '/', name: 'login')]
    public function login(
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        TokenStorageInterface $tokenStorage,
        EventDispatcherInterface $eventDispatcher,
        ): Response
    {
        $erro = null;

        if ($request->isMethod("POST")) {
            $token = $request->request->get("_csrf_token");

            if ($this->isCsrfTokenValid("logar", $token)) {
                $email = $request->request->get("email");
                $request->getSession()->set("email",$email);
                $usuario = $userRepository->buscarEmailUser($email);

                if ($usuario) {
                    $senha = $request->request->get("senha");
                    if ($passwordHasher->isPasswordValid($usuario, $senha)) {
                        
                        $token = new UsernamePasswordToken($usuario, 'main', $usuario->getRoles());
                        $tokenStorage->setToken($token);
                        $event = new InteractiveLoginEvent($request, $token);
                        $eventDispatcher->dispatch($event); 

                        return $this->redirectToRoute('home');
                    } else {
                        $this->addFlash("danger","Senha Inválida!");
                        return $this->redirectToRoute("login");
                    }
                } else { 
                    $this->addFlash("danger","Usuário não encontrado!");
                    return $this->redirectToRoute("login");
                }
            } else {
                $this->addFlash("danger","Token CRSF inválido!");
                return $this->redirectToRoute("login");
            }
        }

        return $this->render('Login/login.html.twig', [
            "email" => NULL
        ]);
    }

    #[Route(path: '/logout', name: 'logout')]
    public function logout(): void
    {
        throw new \LogicException(message: 'This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}