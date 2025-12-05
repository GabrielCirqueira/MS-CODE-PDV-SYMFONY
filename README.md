# 🛒 MS-CODE-PDV-SYMFONY

<div align="center">

![Logo](assets/img/logotipo.svg)

**Sistema de Ponto de Venda (PDV) completo desenvolvido com Symfony 7.1**

[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php&logoColor=white)](https://www.php.net/)
[![Symfony](https://img.shields.io/badge/Symfony-7.1-000000?style=flat&logo=symfony&logoColor=white)](https://symfony.com/)
[![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?style=flat&logo=docker&logoColor=white)](https://www.docker.com/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql&logoColor=white)](https://www.mysql.com/)

</div>

---

## 📋 Índice

- [Sobre o Projeto](#-sobre-o-projeto)
- [Funcionalidades](#-funcionalidades)
- [Tecnologias Utilizadas](#-tecnologias-utilizadas)
- [Instalação com Docker](#-instalação-com-docker-recomendado)
- [Estrutura do Projeto](#-estrutura-do-projeto)
- [Uso](#-uso)
- [Comandos Úteis](#-comandos-úteis)
- [Contribuição](#-contribuição)
- [Licença](#-licença)

---

## 🎯 Sobre o Projeto

O **MS-CODE-PDV-SYMFONY** é um sistema completo de gerenciamento de Ponto de Venda desenvolvido com **Symfony 7.1**, utilizando a arquitetura **MVC** (Model-View-Controller). 

Este sistema oferece uma solução robusta para gerenciar produtos, categorias, clientes, estoque e vendas de maneira eficiente, com recursos avançados de controle de acesso e validações.

### ✨ Características Principais

- 🐳 **100% Dockerizado** - Setup automático com um único comando
- 🔐 **Sistema de Permissões Avançado** - Controle granular de acesso por função
- 📦 **Gerenciamento Completo de Estoque** - Controle de produtos e categorias
- 🛒 **Carrinho de Compras** - Sistema completo de vendas
- 👥 **Gestão de Clientes** - Com validação de CPF via API externa
- 📊 **Dashboard Administrativo** - Interface intuitiva e responsiva
- 🎨 **Design Moderno** - Interface clean com Twig e Asset Mapper

---

## 🚀 Funcionalidades

### Gerenciamento de Produtos
- ✅ CRUD completo de produtos
- ✅ Categorização de produtos
- ✅ Controle de quantidade em estoque
- ✅ Ativação/desativação de produtos
- ✅ Precificação em centavos (precisão máxima)

### Gerenciamento de Clientes
- ✅ Cadastro de clientes com validação de CPF
- ✅ Validação automática via API: `https://api.invertexto.com/v1/validator`
- ✅ Histórico de compras
- ✅ Status ativo/inativo

### Carrinho e Vendas
- ✅ Sistema de carrinho de compras
- ✅ Múltiplos carrinhos simultâneos
- ✅ Controle de status (aberto, aguardando, finalizado)
- ✅ Cálculo automático de totais

### Sistema de Usuários e Permissões
- ✅ Autenticação segura com Symfony Security
- ✅ Sistema de roles (ADMIN, GERENTE, VENDEDOR, ESTOQUE, FINANCEIRO)
- ✅ Controle de acesso por permissão
- ✅ Senhas criptografadas

### Recursos Adicionais
- ✅ Validação de formulários
- ✅ Mensagens Flash personalizadas
- ✅ Logs de ações importantes
- ✅ API REST (parcial)
- ✅ Dados mockados para desenvolvimento

---

## 🛠 Tecnologias Utilizadas

### Backend
- **PHP 8.2+** - Linguagem base
- **Symfony 7.1** - Framework PHP
- **Doctrine ORM** - Mapeamento objeto-relacional
- **Twig** - Template engine
- **Symfony Security** - Autenticação e autorização

### Frontend
- **Stimulus** - Framework JavaScript leve
- **Turbo** - Navegação SPA-like
- **Asset Mapper** - Gerenciamento de assets
- **CSS Vanilla** - Estilização customizada

### Banco de Dados
- **MySQL 8.0** - Banco de dados principal

### DevOps
- **Docker** - Containerização
- **Docker Compose** - Orquestração de containers
- **phpMyAdmin** - Interface web para MySQL

---

## 🐳 Instalação com Docker (Recomendado)

### Pré-requisitos

- [Docker](https://www.docker.com/get-started) instalado
- [Docker Compose](https://docs.docker.com/compose/install/) instalado
- 4GB de RAM disponível

### Setup Automático (1 comando!)

```bash
# Clone o repositório
git clone https://github.com/GabrielCirqueira/MS-CODE-PDV-SYMFONY.git
cd MS-CODE-PDV-SYMFONY

# Execute o setup completo
make setup
```

O comando `make setup` irá automaticamente:
1. ✅ Construir as imagens Docker
2. ✅ Iniciar os containers (App, MySQL, phpMyAdmin)
3. ✅ Aguardar o MySQL estar pronto
4. ✅ Instalar dependências do Composer
5. ✅ Executar as migrations do banco
6. ✅ Popular o banco com dados mockados
7. ✅ Limpar o cache

### 🎉 Pronto! Acesse:

| Serviço | URL | Descrição |
|---------|-----|-----------|
| 🌐 **Aplicação** | http://localhost:8080 | Sistema PDV principal |
| 📊 **phpMyAdmin** | http://localhost:8081 | Interface do banco de dados |

### 🔑 Credenciais de Acesso

#### Usuários do Sistema

| Perfil | Email | Senha | Permissões |
|--------|-------|-------|------------|
| 👑 **Admin** | admin@admin.com | admin123 | Acesso total |
| 👨‍💼 **Gerente** | gerente@pdv.com | gerente123 | Gestão completa |
| 🛒 **Vendedor** | vendedor@pdv.com | vendedor123 | Vendas e clientes |
| 📦 **Estoquista** | estoque@pdv.com | estoque123 | Controle de estoque |

#### Banco de Dados (phpMyAdmin)

- **Servidor:** `database`
- **Usuário:** `root`
- **Senha:** `root_password`
- **Database:** `ms_code_pdv_symfony`

---

## 📁 Estrutura do Projeto

```
MS-CODE-PDV-SYMFONY/
├── 🐳 Docker/
│   ├── Dockerfile                 # Imagem da aplicação
│   ├── docker-compose.yml         # Orquestração dos serviços
│   └── .dockerignore              # Arquivos ignorados no build
│
├── 📂 src/
│   ├── Command/                   # Comandos Console
│   │   ├── CreatePermissaoCommand.php
│   │   ├── CriarUsuarioCommand.php
│   │   └── PopularBancoCommand.php    # 🆕 Comando para popular DB
│   ├── Controller/                # Controllers MVC
│   │   ├── Carrinho/
│   │   ├── Categorias/
│   │   ├── Cliente/
│   │   ├── Home/
│   │   ├── Login/
│   │   ├── Produtos/
│   │   └── User/
│   ├── Entity/                    # Entidades Doctrine
│   │   ├── Carrinho.php
│   │   ├── Categoria.php
│   │   ├── Cliente.php
│   │   ├── Item.php
│   │   ├── Permissao.php
│   │   ├── Produto.php
│   │   ├── User.php
│   │   └── Vendas.php
│   ├── Repository/                # Repositórios Doctrine
│   ├── Service/                   # Lógica de negócio
│   └── Exception/                 # Exceções customizadas
│
├── 📄 templates/                  # Views Twig
│   ├── base.html.twig
│   ├── layout.html.twig
│   ├── carrinho/
│   ├── categorias/
│   ├── cliente/
│   ├── produtos/
│   └── usuarios/
│
├── 🎨 assets/                     # Assets frontend
│   ├── js/
│   ├── styles/
│   ├── img/
│   └── controllers/
│
├── ⚙️ config/                     # Configurações
│   ├── packages/
│   ├── routes/
│   └── services.yaml
│
├── 📜 migrations/                 # Migrations do banco
├── 🧪 tests/                      # Testes automatizados
├── 📝 setup.sh                    # Script de instalação
├── 📖 README.md                   # Este arquivo
└── 🔧 .env                        # Variáveis de ambiente

```

---

## 💻 Uso

### Gerenciamento de Produtos

```bash
# Via interface web
1. Acesse http://localhost:8080
2. Faça login com credenciais de admin
3. Navegue até "Produtos"
4. Adicione, edite ou remova produtos
```

### Gerenciamento de Clientes

```bash
# Via interface web
1. Acesse "Clientes" no menu
2. Adicione novo cliente
3. O CPF será validado automaticamente via API
```

### Realizar Vendas

```bash
1. Selecione um cliente
2. Adicione produtos ao carrinho
3. Finalize a venda
4. Estoque é atualizado automaticamente
```

---

## 🔧 Comandos Úteis (Makefile)

### Ver todos os comandos disponíveis
```bash
make help
```

### Setup e Instalação
```bash
make setup              # Setup completo do projeto (primeira vez)
make install            # Instala dependências do Composer
```

### Controle de Containers
```bash
make start              # Inicia todos os containers
make stop               # Para todos os containers
make restart            # Reinicia todos os containers
make down               # Para e remove todos os containers
make build              # Reconstrói as imagens Docker
make rebuild            # Reconstrói do zero (sem cache)
make ps                 # Lista status dos containers
```

### Logs
```bash
make logs               # Mostra logs de todos os containers
make logs-app           # Logs apenas da aplicação
make logs-db            # Logs apenas do MySQL
make logs-phpmyadmin    # Logs apenas do phpMyAdmin
```

### Acesso aos Containers
```bash
make shell              # Acessa shell do container da aplicação
make shell-db           # Acessa shell do MySQL
make mysql              # Acessa MySQL CLI
make phpmyadmin         # Abre phpMyAdmin no navegador
make app                # Abre a aplicação no navegador
```

### Banco de Dados
```bash
make migrate            # Executa migrations pendentes
make migrate-status     # Status das migrations
make migrate-diff       # Gera nova migration
make seed               # Popula banco com dados mockados
make db-create          # Cria o banco de dados
make db-drop            # Remove o banco de dados
make db-reset           # Reseta completamente o banco
```

### Symfony Console
```bash
make console            # Acessa console do Symfony
make cache-clear        # Limpa o cache
make cache-warmup       # Aquece o cache
make routes             # Lista todas as rotas
make controllers        # Lista todos os controllers
make create-user        # Cria novo usuário
make create-permission  # Cria nova permissão
```

### Testes
```bash
make test               # Executa os testes
make test-coverage      # Testes com cobertura
```

### Limpeza
```bash
make clean              # Remove containers, volumes e cache
make clean-cache        # Remove apenas o cache
make prune              # Remove recursos Docker não utilizados
```

### Desenvolvimento
```bash
make fix-permissions    # Corrige permissões de arquivos
make composer-update    # Atualiza dependências
make composer-require PACKAGE=nome/pacote  # Instala novo pacote
make dump-autoload      # Atualiza autoload do Composer
make assets-install     # Instala os assets
```

### Informações
```bash
make info               # Mostra informações do projeto
make version            # Mostra versões das ferramentas
```

---

## 📊 Dados Mockados

O comando `php bin/console app:popular-banco` cria:

- **5 Permissões** (Admin, Gerente, Vendedor, Estoque, Financeiro)
- **4 Usuários** com diferentes permissões
- **8 Categorias** de produtos
- **20+ Produtos** diversos com estoque
- **10 Clientes** com CPF válido

Perfeito para desenvolvimento e testes!

---

## 🧪 Testes

```bash
# Executar todos os testes
docker-compose exec app php bin/phpunit

# Executar testes específicos
docker-compose exec app php bin/phpunit tests/Controller/

# Com cobertura de código
docker-compose exec app php bin/phpunit --coverage-html coverage/
```

---

## 🤝 Contribuição

Contribuições são bem-vindas! Para contribuir:

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

---

## 📝 Licença

Este projeto está sob a licença proprietária. Veja o arquivo `LICENSE` para mais detalhes.

---

## 👨‍💻 Autor

**Gabriel Cirqueira**

- GitHub: [@GabrielCirqueira](https://github.com/GabrielCirqueira)
- LinkedIn: [Gabriel Cirqueira](https://linkedin.com/in/gabriel-cirqueira)

---

## 📞 Suporte

Se você encontrar algum problema ou tiver sugestões:

1. Abra uma [Issue](https://github.com/GabrielCirqueira/MS-CODE-PDV-SYMFONY/issues)
2. Entre em contato via LinkedIn

---

<div align="center">

**⭐ Se este projeto foi útil para você, considere dar uma estrela!**

Feito com ❤️ e ☕ por [Gabriel Cirqueira](https://github.com/GabrielCirqueira)

</div>
