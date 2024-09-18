<?php 

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class InserirUserController extends AbstractController
{
    #[Route('/inserir', name: 'inserir_user')]
    public function index(EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User;
        $user->setEmail("gabrielcirqueira711@gmail.com");

        $hashedPassword = $passwordHasher->hashPassword($user, "fdf");
        $user->setPassword($hashedPassword);

        $msg = "NADA";
        
        try {
        
            $em->persist($user);
            $em->flush();
            $msg = "User SALVO COM SUCESSO";
        } catch (\Exception $e) {
            $msg = "ERRO AO CADASTRAR, ERRO: " . $e->getMessage();
        }

        return new Response("<h1>{$msg}</h1>");
    }
}
