<?php

namespace App\Command;

use App\Entity\Categoria;
use App\Entity\Cliente;
use App\Entity\Produto;
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
    name: 'app:popular-banco',
    description: 'Popula o banco de dados com dados mockados para desenvolvimento',
)]
class PopularBancoCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('🚀 Populando banco de dados com dados mockados');

        try {
            // Criar Permissões
            $io->section('📋 Criando Permissões...');
            $permissoes = $this->criarPermissoes($io);

            // Criar Usuários
            $io->section('👤 Criando Usuários...');
            $this->criarUsuarios($io, $permissoes);

            // Criar Categorias
            $io->section('📁 Criando Categorias...');
            $categorias = $this->criarCategorias($io);

            // Criar Produtos
            $io->section('📦 Criando Produtos...');
            $this->criarProdutos($io, $categorias);

            // Criar Clientes
            $io->section('👥 Criando Clientes...');
            $this->criarClientes($io);

            $io->success('✅ Banco de dados populado com sucesso!');
            $io->note([
                'Você pode fazer login com:',
                '👉 Admin: admin@admin.com / senha: admin123',
                '👉 Gerente: gerente@pdv.com / senha: gerente123',
                '👉 Vendedor: vendedor@pdv.com / senha: vendedor123',
            ]);

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('❌ Erro ao popular banco: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    private function criarPermissoes(SymfonyStyle $io): array
    {
        $permissoesData = [
            // Permissões de ROLE
            ['nome' => 'ROLE_ADMIN', 'grupo' => 'Administração', 'descricao' => 'Administrador do Sistema'],
            ['nome' => 'ROLE_GERENTE', 'grupo' => 'Gestão', 'descricao' => 'Gerente da Loja'],
            ['nome' => 'ROLE_VENDEDOR', 'grupo' => 'Vendas', 'descricao' => 'Vendedor'],
            ['nome' => 'ROLE_ESTOQUE', 'grupo' => 'Operacional', 'descricao' => 'Controle de Estoque'],
            ['nome' => 'ROLE_FINANCEIRO', 'grupo' => 'Financeiro', 'descricao' => 'Setor Financeiro'],
            
            // Permissões Granulares - Produtos
            ['nome' => 'Adicionar-Produtos', 'grupo' => 'Produtos', 'descricao' => 'Permite adicionar novos produtos'],
            ['nome' => 'Ver-Produtos', 'grupo' => 'Produtos', 'descricao' => 'Permite visualizar produtos'],
            
            // Permissões Granulares - Categorias
            ['nome' => 'Adicionar-Categoria', 'grupo' => 'Categorias', 'descricao' => 'Permite adicionar categorias'],
            ['nome' => 'Ver-Categorias', 'grupo' => 'Categorias', 'descricao' => 'Permite visualizar categorias'],
            
            // Permissões Granulares - Clientes
            ['nome' => 'adicionar-cliente', 'grupo' => 'Clientes', 'descricao' => 'Permite adicionar clientes'],
            ['nome' => 'ver-clientes', 'grupo' => 'Clientes', 'descricao' => 'Permite visualizar clientes'],
            
            // Permissões Granulares - Vendas
            ['nome' => 'nova-venda', 'grupo' => 'Vendas', 'descricao' => 'Permite criar nova venda'],
            ['nome' => 'listar-vendas', 'grupo' => 'Vendas', 'descricao' => 'Permite listar vendas'],
            
            // Permissões Granulares - Usuários
            ['nome' => 'criar-usuario', 'grupo' => 'Usuários', 'descricao' => 'Permite criar usuários'],
            ['nome' => 'ver-usuarios', 'grupo' => 'Usuários', 'descricao' => 'Permite visualizar usuários'],
        ];

        $permissoes = [];
        $permissaoRepository = $this->entityManager->getRepository(Permissao::class);
        
        foreach ($permissoesData as $data) {
            // Verificar se a permissão já existe
            $permissaoExistente = $permissaoRepository->findOneBy(['nome' => $data['nome']]);
            
            if ($permissaoExistente) {
                $permissoes[$data['nome']] = $permissaoExistente;
                $io->writeln("  ⊙ {$data['nome']} (já existe)");
            } else {
                $permissao = new Permissao();
                $permissao->setNome($data['nome']);
                $permissao->setGrupo($data['grupo']);
                $permissao->setDescricao($data['descricao']);
                $this->entityManager->persist($permissao);
                $permissoes[$data['nome']] = $permissao;
                $io->writeln("  ✓ {$data['nome']} ({$data['grupo']}) - {$data['descricao']}");
            }
        }

        $this->entityManager->flush();
        return $permissoes;
    }

    private function criarUsuarios(SymfonyStyle $io, array $permissoes): void
    {
        $usuariosData = [
            [
                'nome' => 'Administrador',
                'email' => 'admin@admin.com',
                'senha' => 'admin123',
                'permissoes' => 'all', // Todas as permissões
            ],
            [
                'nome' => 'João Gerente',
                'email' => 'gerente@pdv.com',
                'senha' => 'gerente123',
                'permissoes' => [
                    'ROLE_GERENTE',
                    'Ver-Produtos',
                    'Adicionar-Produtos',
                    'Ver-Categorias',
                    'Adicionar-Categoria',
                    'ver-clientes',
                    'adicionar-cliente',
                    'nova-venda',
                    'listar-vendas',
                    'ver-usuarios',
                ],
            ],
            [
                'nome' => 'Maria Vendedora',
                'email' => 'vendedor@pdv.com',
                'senha' => 'vendedor123',
                'permissoes' => [
                    'ROLE_VENDEDOR',
                    'Ver-Produtos',
                    'ver-clientes',
                    'adicionar-cliente',
                    'nova-venda',
                ],
            ],
            [
                'nome' => 'Carlos Estoquista',
                'email' => 'estoque@pdv.com',
                'senha' => 'estoque123',
                'permissoes' => [
                    'ROLE_ESTOQUE',
                    'Ver-Produtos',
                    'Adicionar-Produtos',
                    'Ver-Categorias',
                    'Adicionar-Categoria',
                ],
            ],
        ];

        $userRepository = $this->entityManager->getRepository(User::class);
        
        foreach ($usuariosData as $data) {
            // Verificar se o usuário já existe
            $userExistente = $userRepository->findOneBy(['email' => $data['email']]);
            
            if ($userExistente) {
                $io->writeln("  ⊙ {$data['nome']} (já existe)");
            } else {
                $user = new User();
                $user->setNome($data['nome']);
                $user->setEmail($data['email']);
                $hashedPassword = $this->passwordHasher->hashPassword($user, $data['senha']);
                $user->setPassword($hashedPassword);
                
                // Adicionar permissões
                if ($data['permissoes'] === 'all') {
                    // Admin recebe TODAS as permissões
                    foreach ($permissoes as $permissao) {
                        $user->addPermissao($permissao);
                    }
                } else {
                    // Outros usuários recebem suas permissões específicas
                    foreach ($data['permissoes'] as $nomePermissao) {
                        if (isset($permissoes[$nomePermissao])) {
                            $user->addPermissao($permissoes[$nomePermissao]);
                        }
                    }
                }
                
                $user->setAtivo(true);
                
                $this->entityManager->persist($user);
                $io->writeln("  ✓ {$data['nome']} ({$data['email']})");
            }
        }

        $this->entityManager->flush();
    }

    private function criarCategorias(SymfonyStyle $io): array
    {
        $categoriasData = [
            ['nome' => 'Eletrônicos'],
            ['nome' => 'Alimentos'],
            ['nome' => 'Vestuário'],
            ['nome' => 'Livros'],
            ['nome' => 'Esportes'],
            ['nome' => 'Casa e Jardim'],
            ['nome' => 'Brinquedos'],
            ['nome' => 'Beleza'],
        ];

        $categorias = [];
        $categoriaRepository = $this->entityManager->getRepository(Categoria::class);
        
        foreach ($categoriasData as $data) {
            // Verificar se a categoria já existe
            $categoriaExistente = $categoriaRepository->findOneBy(['nome' => $data['nome']]);
            
            if ($categoriaExistente) {
                $categorias[] = $categoriaExistente;
                $io->writeln("  ⊙ {$data['nome']} (já existe)");
            } else {
                $categoria = new Categoria();
                $categoria->setNome($data['nome']);
                $this->entityManager->persist($categoria);
                $categorias[] = $categoria;
                $io->writeln("  ✓ {$data['nome']}");
            }
        }

        $this->entityManager->flush();
        return $categorias;
    }

    private function criarProdutos(SymfonyStyle $io, array $categorias): void
    {
        $produtoRepository = $this->entityManager->getRepository(Produto::class);
        
        // Verificar se já existem produtos
        $produtosExistentes = $produtoRepository->findAll();
        if (count($produtosExistentes) > 0) {
            $io->writeln("  ⊙ Já existem " . count($produtosExistentes) . " produtos no banco. Pulando criação...");
            return;
        }
        
        $produtosData = [
            // Eletrônicos
            ['nome' => 'Notebook Dell', 'descricao' => 'Notebook Core i5 8GB', 'valor' => 350000, 'quantidade' => 15, 'categoria' => 0, 'ativo' => true],
            ['nome' => 'Mouse Logitech', 'descricao' => 'Mouse óptico USB', 'valor' => 8990, 'quantidade' => 50, 'categoria' => 0, 'ativo' => true],
            ['nome' => 'Teclado Mecânico', 'descricao' => 'Teclado RGB', 'valor' => 45000, 'quantidade' => 25, 'categoria' => 0, 'ativo' => true],
            ['nome' => 'Monitor LG 24"', 'descricao' => 'Monitor Full HD', 'valor' => 89900, 'quantidade' => 10, 'categoria' => 0, 'ativo' => true],
            
            // Alimentos
            ['nome' => 'Café Pilão 500g', 'descricao' => 'Café torrado e moído', 'valor' => 1890, 'quantidade' => 100, 'categoria' => 1, 'ativo' => true],
            ['nome' => 'Arroz Tio João 5kg', 'descricao' => 'Arroz tipo 1', 'valor' => 2550, 'quantidade' => 80, 'categoria' => 1, 'ativo' => true],
            ['nome' => 'Feijão Carioca 1kg', 'descricao' => 'Feijão tipo 1', 'valor' => 890, 'quantidade' => 120, 'categoria' => 1, 'ativo' => true],
            ['nome' => 'Açúcar Cristal 1kg', 'descricao' => 'Açúcar refinado', 'valor' => 450, 'quantidade' => 150, 'categoria' => 1, 'ativo' => true],
            
            // Vestuário
            ['nome' => 'Camiseta Básica', 'descricao' => 'Camiseta 100% algodão', 'valor' => 3990, 'quantidade' => 200, 'categoria' => 2, 'ativo' => true],
            ['nome' => 'Calça Jeans', 'descricao' => 'Calça jeans masculina', 'valor' => 12990, 'quantidade' => 75, 'categoria' => 2, 'ativo' => true],
            ['nome' => 'Tênis Nike', 'descricao' => 'Tênis esportivo', 'valor' => 29900, 'quantidade' => 30, 'categoria' => 2, 'ativo' => true],
            
            // Livros
            ['nome' => 'Clean Code', 'descricao' => 'Livro de programação', 'valor' => 8500, 'quantidade' => 20, 'categoria' => 3, 'ativo' => true],
            ['nome' => 'Design Patterns', 'descricao' => 'Padrões de projeto', 'valor' => 9500, 'quantidade' => 15, 'categoria' => 3, 'ativo' => true],
            
            // Esportes
            ['nome' => 'Bola de Futebol', 'descricao' => 'Bola profissional', 'valor' => 8990, 'quantidade' => 40, 'categoria' => 4, 'ativo' => true],
            ['nome' => 'Halteres 5kg', 'descricao' => 'Par de halteres', 'valor' => 12000, 'quantidade' => 25, 'categoria' => 4, 'ativo' => true],
            
            // Casa e Jardim
            ['nome' => 'Jogo de Panelas', 'descricao' => '5 peças antiaderente', 'valor' => 29900, 'quantidade' => 18, 'categoria' => 5, 'ativo' => true],
            ['nome' => 'Aspirador de Pó', 'descricao' => '1200W', 'valor' => 45000, 'quantidade' => 12, 'categoria' => 5, 'ativo' => true],
            
            // Brinquedos
            ['nome' => 'LEGO Classic', 'descricao' => '500 peças', 'valor' => 18990, 'quantidade' => 35, 'categoria' => 6, 'ativo' => true],
            ['nome' => 'Boneca Barbie', 'descricao' => 'Boneca articulada', 'valor' => 7990, 'quantidade' => 45, 'categoria' => 6, 'ativo' => true],
            
            // Beleza
            ['nome' => 'Shampoo', 'descricao' => 'Shampoo 400ml', 'valor' => 2590, 'quantidade' => 60, 'categoria' => 7, 'ativo' => true],
        ];

        foreach ($produtosData as $data) {
            $produto = new Produto();
            $produto->setNome($data['nome']);
            $produto->setDescricao($data['descricao']);
            $produto->setValorUnitario($data['valor']); // valor em centavos
            $produto->setQuantidade($data['quantidade']);
            $produto->setCategoria($categorias[$data['categoria']]);
            $produto->setAtivo($data['ativo']);
            
            $this->entityManager->persist($produto);
            $precoFormatado = number_format($data['valor'] / 100, 2, ',', '.');
            $status = $data['ativo'] ? '✓' : '✗';
            $io->writeln("  {$status} {$data['nome']} - R$ {$precoFormatado} ({$data['quantidade']} un)");
        }

        $this->entityManager->flush();
    }

    private function criarClientes(SymfonyStyle $io): void
    {
        $clienteRepository = $this->entityManager->getRepository(Cliente::class);
        
        // Verificar se já existem clientes
        $clientesExistentes = $clienteRepository->findAll();
        if (count($clientesExistentes) > 0) {
            $io->writeln("  ⊙ Já existem " . count($clientesExistentes) . " clientes no banco. Pulando criação...");
            return;
        }
        
        $clientesData = [
            ['nome' => 'José Silva', 'cpf' => '12345678901', 'ativo' => true],
            ['nome' => 'Ana Santos', 'cpf' => '98765432109', 'ativo' => true],
            ['nome' => 'Pedro Oliveira', 'cpf' => '45678912301', 'ativo' => true],
            ['nome' => 'Mariana Costa', 'cpf' => '78912345601', 'ativo' => true],
            ['nome' => 'Carlos Souza', 'cpf' => '32165498701', 'ativo' => true],
            ['nome' => 'Juliana Lima', 'cpf' => '65498732101', 'ativo' => true],
            ['nome' => 'Roberto Alves', 'cpf' => '14725836901', 'ativo' => true],
            ['nome' => 'Fernanda Ribeiro', 'cpf' => '36925814701', 'ativo' => true],
            ['nome' => 'Paulo Mendes', 'cpf' => '85274196301', 'ativo' => true],
            ['nome' => 'Cliente Inativo', 'cpf' => '11122233344', 'ativo' => false],
        ];

        foreach ($clientesData as $data) {
            $cliente = new Cliente();
            $cliente->setNome($data['nome']);
            $cliente->setCpf($data['cpf']);
            $cliente->setAtivo($data['ativo']);
            
            $this->entityManager->persist($cliente);
            $status = $data['ativo'] ? '✓' : '✗';
            $io->writeln("  {$status} {$data['nome']} - CPF: {$data['cpf']}");
        }

        $this->entityManager->flush();
    }
}
