# MS-CODE-ESTOQUE

## Introdução

O **MS-CODE-ESTOQUE** é um sistema de gerenciamento de estoque desenvolvido com o **Symfony**, utilizando a arquitetura **MVC** (Model-View-Controller). Este sistema permite gerenciar produtos, categorias e estoque de maneira eficiente, além de oferecer recursos como carrinho de compras, status ativo/inativo de clientes e produtos, validação de CPF ao cadastrar clientes, e um sistema de permissões para controle de acesso dos usuários.

## Funcionalidades

- **Gerenciamento de Estoque**: Adicionar, editar, excluir e visualizar produtos e categorias.
- **Carrinho de Compras**: Implementação de carrinho para simulação de compras de produtos.
- **Status de Clientes e Produtos**: Defina status de clientes e produtos como "ativo" ou "inativo".
- **Validação de CPF**: Validação de CPF ao cadastrar clientes via API externa: `https://api.invertexto.com/v1/validator`.
- **Verificação de Dados de Formulários**: Verificação adicional ao enviar dados através de formulários.
- **Controle de Permissões**: O usuário administrador pode cadastrar novos usuários e definir permissões específicas, garantindo que cada usuário tenha acesso apenas às áreas permitidas.

## Requisitos

Para rodar este projeto localmente, você precisa de:

- **PHP 8.0 ou superior**
- **Composer** (gerenciador de dependências)
- **Banco de Dados**: MySQL ou PostgreSQL (configurável no arquivo `.env`)

## Instalação

### 1. Clonar o Repositório

Primeiro, clone o repositório para o seu ambiente local:

```bash
git clone https://github.com/usuario/ms-code-estoque.git
```

### 2. Instalar Dependências

```bash
git composer install
```

### 3. Executar as Migrations

```bash
git php bin/console doctrine:migrations:migrate
```

### 5. Criar Permissões Básicas

```bash
git php bin/console app:criar-permissoes
```


### 6. Criar o Usuário Administrador

```bash
git php bin/console app:adicionar-usuario
```

### 8. Criar Novas Permissões (Se necessário)

```bash
git php bin/console app:adicionar-usuario
```
