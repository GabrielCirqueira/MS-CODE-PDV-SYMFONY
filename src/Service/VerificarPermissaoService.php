<?php 

namespace App\Service;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;

class VerificarPermissaoService
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function execute(string $permissaoName): bool
    {
        $user = $this->security->getUser();
        if ($user instanceof User) {
            foreach ($user->getPermissoes() as $permissao) {
                if ($permissao->getNome() === $permissaoName) {
                    return true;
                }
            }
        }

        return false;
    }
}
