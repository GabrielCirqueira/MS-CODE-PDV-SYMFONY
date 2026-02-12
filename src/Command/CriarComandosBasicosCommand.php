<?php

namespace App\Command;

use App\Entity\Permissao;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:criar-permissoes',
    description: 'Cria permissões básicas no sistema.',
)]
class CriarComandosBasicosCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Criar Permissões Básicas');

        $permissoes = [
            ['nome' => 'Adicionar-Categoria', 'grupo' => 'Categorias', 'descricao' => 'Permissão para adicionar novas categorias.'],
            ['nome' => 'Ver-Categorias', 'grupo' => 'Categorias', 'descricao' => 'Permissão para visualizar categorias.'],
            ['nome' => 'adicionar-cliente', 'grupo' => 'Clientes', 'descricao' => 'Permissão para adicionar novos clientes.'],
            ['nome' => 'ver-clientes', 'grupo' => 'Clientes', 'descricao' => 'Permissão para visualizar clientes.'],
            ['nome' => 'nova-venda', 'grupo' => 'Vendas', 'descricao' => 'Permissão para criar novas vendas.'],
            ['nome' => 'listar-vendas', 'grupo' => 'Vendas', 'descricao' => 'Permissão para listar vendas existentes.'],
            ['nome' => 'criar-usuario', 'grupo' => 'Usuários', 'descricao' => 'Permissão para criar novos usuários.'],
            ['nome' => 'ver-usuarios', 'grupo' => 'Usuários', 'descricao' => 'Permissão para visualizar usuários.'],
            ['nome' => 'Adicionar-Produtos', 'grupo' => 'Produtos', 'descricao' => 'Permissão para adicionar novos produtos.'],
            ['nome' => 'Ver-Produtos', 'grupo' => 'Produtos', 'descricao' => 'Permissão para visualizar produtos.'],
        ];

        foreach ($permissoes as $permissaoData) {
            $existe = $this->entityManager->getRepository(Permissao::class)->findOneBy(['nome' => $permissaoData['nome']]);

            if ($existe) {
                $io->text("Permissão '{$permissaoData['nome']}' já existe. Pulando...");
                continue;
            }

            $permissao = new Permissao();
            $permissao->setNome($permissaoData['nome'])
                ->setGrupo($permissaoData['grupo'])
                ->setDescricao($permissaoData['descricao']);

            $this->entityManager->persist($permissao);
            $io->success("Permissão '{$permissaoData['nome']}' criada com sucesso.");
        }

        $this->entityManager->flush();

        $io->success('Todas as permissões foram verificadas e criadas.');

        return Command::SUCCESS;
    }
}
