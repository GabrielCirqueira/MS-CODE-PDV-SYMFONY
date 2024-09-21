<?php 

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService 
{
    private $passwordHasher;
    private $userRepository;

    public function __construct(UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository)
    {
        $this->passwordHasher = $passwordHasher;
        $this->userRepository = $userRepository;
    }

    public function registarUsuario($dados): bool
    {
        $usuario = new User();
        $usuario->setEmail($dados["email"]);
        $usuario->setNome($dados["nome"]);
        $usuario->setPassword($this->passwordHasher->hashPassword($usuario, $dados["senha"]));
        
        $this->userRepository->save($usuario, true);

        return true;
    }
}
