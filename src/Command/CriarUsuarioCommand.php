<?php

namespace App\Command;

use App\Entity\User;
use App\Entity\Permissao;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:adicionar-usuario',
    description: 'Cria um novo usuário no sistema e associa todas as permissões existentes.',
)]
class CriarUsuarioCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Criar um novo usuário');

        $nome = $io->ask('Digite o nome do usuário');
        $email = $io->ask('Digite o email do usuário');
        $senha = $io->askHidden('Digite a senha do usuário', function ($senha) use ($io) {
            if (strlen($senha) < 2) {
                $io->error('A senha deve ter pelo menos 6 caracteres.');
                return false;
            }
            return $senha;
        });

        if (!$nome || !$email || !$senha) {
            $io->error('Todos os campos são obrigatórios!');
            return Command::FAILURE;
        }

        $usuario = new User();
        $usuario->setNome($nome)
            ->setEmail($email)
            ->setAtivo(true)
            ->setPassword($this->passwordHasher->hashPassword($usuario, $senha));

        $permissoes = $this->entityManager->getRepository(Permissao::class)->findAll();

        if (empty($permissoes)) {
            $io->warning('Nenhuma permissão encontrada no sistema. O usuário será criado sem permissões.');
        } else {
            foreach ($permissoes as $permissao) {
                $usuario->addPermissao($permissao);
            }
            $io->success('Todas as permissões existentes foram associadas ao usuário.');
        }

        $this->entityManager->persist($usuario);
        $this->entityManager->flush();

        $io->success('Usuário criado com sucesso!');

        return Command::SUCCESS;
    }
}
