<?php 

namespace App\Command;

use App\Entity\Permissao;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-permissao',
    description: 'Cria uma nova permissão no sistema',
)]
class CreatePermissaoCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('nome', InputArgument::REQUIRED, 'O nome único da permissão')
            ->addArgument('grupo', InputArgument::REQUIRED, 'O grupo da permissão')
            ->addArgument('descricao', InputArgument::OPTIONAL, 'Uma descrição da permissão');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $nome = $input->getArgument('nome');
        $grupo = strtoupper($input->getArgument('grupo'));
        $descricao = $input->getArgument('descricao') ?? '';

        $permissaoExiste = $this->entityManager->getRepository(Permissao::class)->findOneBy(['nome' => $nome]);
        if ($permissaoExiste) {
            $output->writeln('<error>Permissão já existe!</error>');
            return Command::FAILURE;
        }

        $permissao = new Permissao();
        $permissao->setNome($nome);
        $permissao->setGrupo($grupo);
        $permissao->setDescricao($descricao);

        $this->entityManager->persist($permissao);
        $this->entityManager->flush();

        $output->writeln('<info>Permissão criada com sucesso!</info>');
        $output->writeln("Nome: $nome");
        if ($descricao) {
            $output->writeln("Descrição: $descricao");
        }

        return Command::SUCCESS;
    }
}
