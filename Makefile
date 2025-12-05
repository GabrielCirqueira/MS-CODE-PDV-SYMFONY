.PHONY: help setup start stop restart build rebuild logs shell clean migrate seed test cache-clear down ps status db-create db-drop composer-install phpmyadmin

# Cores para output
GREEN  := $(shell tput -Txterm setaf 2)
YELLOW := $(shell tput -Txterm setaf 3)
BLUE   := $(shell tput -Txterm setaf 4)
RED    := $(shell tput -Txterm setaf 1)
RESET  := $(shell tput -Txterm sgr0)

# Configurações
DOCKER_COMPOSE = docker compose
EXEC_APP = $(DOCKER_COMPOSE) exec app
EXEC_DB = $(DOCKER_COMPOSE) exec database

##@ Ajuda

help: ## Mostra esta mensagem de ajuda
	@echo '╔══════════════════════════════════════════════════════════╗'
	@echo '║       MS-CODE-PDV-SYMFONY - Comandos Make               ║'
	@echo '║       Sistema de Ponto de Venda com Symfony             ║'
	@echo '╚══════════════════════════════════════════════════════════╝'
	@echo ''
	@awk 'BEGIN {FS = ":.*##"; printf "Usage: ${BLUE}make${RESET} ${GREEN}<target>${RESET}\n"} /^[a-zA-Z_-]+:.*?##/ { printf "  ${BLUE}%-20s${RESET} %s\n", $$1, $$2 } /^##@/ { printf "\n${YELLOW}%s${RESET}\n", substr($$0, 5) } ' $(MAKEFILE_LIST)
	@echo ''

##@ Setup e Instalação

setup: ## Setup completo do projeto (primeira vez)
	@echo "${YELLOW}═══ Setup Completo do Projeto ═══${RESET}"
	@echo "${GREEN}✓ Parando containers existentes...${RESET}"
	@$(DOCKER_COMPOSE) down -v 2>/dev/null || true
	@echo "${GREEN}✓ Construindo imagens Docker...${RESET}"
	@$(DOCKER_COMPOSE) build --no-cache
	@echo "${GREEN}✓ Iniciando containers...${RESET}"
	@$(DOCKER_COMPOSE) up -d
	@echo "${YELLOW}➜ Aguardando MySQL inicializar (15s)...${RESET}"
	@sleep 15
	@echo "${GREEN}✓ Instalando dependências Composer...${RESET}"
	@$(EXEC_APP) composer install --no-interaction --optimize-autoloader
	@echo "${GREEN}✓ Executando migrations...${RESET}"
	@$(EXEC_APP) php bin/console doctrine:migrations:migrate --no-interaction
	@echo "${GREEN}✓ Populando banco de dados...${RESET}"
	@$(EXEC_APP) php bin/console app:popular-banco --no-interaction
	@echo "${GREEN}✓ Limpando cache...${RESET}"
	@$(EXEC_APP) php bin/console cache:clear
	@echo ""
	@echo "${GREEN}┌─────────────────────────────────────────────────────────┐${RESET}"
	@echo "${GREEN}│  ✓ Setup concluído com sucesso!                        │${RESET}"
	@echo "${GREEN}│                                                         │${RESET}"
	@echo "${GREEN}│  Acesse:                                                │${RESET}"
	@echo "${GREEN}│  🌐 Aplicação:   http://localhost:8080                 │${RESET}"
	@echo "${GREEN}│  📊 phpMyAdmin:  http://localhost:8081                 │${RESET}"
	@echo "${GREEN}│                                                         │${RESET}"
	@echo "${GREEN}│  Credenciais:                                           │${RESET}"
	@echo "${GREEN}│  👤 Admin:    admin@admin.com / admin123               │${RESET}"
	@echo "${GREEN}│  👤 Gerente:  gerente@pdv.com / gerente123             │${RESET}"
	@echo "${GREEN}│  👤 Vendedor: vendedor@pdv.com / vendedor123           │${RESET}"
	@echo "${GREEN}└─────────────────────────────────────────────────────────┘${RESET}"
	@echo ""

install: composer-install ## Instala as dependências do Composer

composer-install: ## Instala dependências do Composer
	@echo "${BLUE}➜ Instalando dependências...${RESET}"
	@$(EXEC_APP) composer install --no-interaction --optimize-autoloader
	@echo "${GREEN}✓ Dependências instaladas!${RESET}"

##@ Docker - Controle de Containers

start: ## Inicia todos os containers
	@echo "${BLUE}➜ Iniciando containers...${RESET}"
	@$(DOCKER_COMPOSE) up -d
	@echo "${GREEN}✓ Containers iniciados!${RESET}"
	@make ps

stop: ## Para todos os containers
	@echo "${YELLOW}➜ Parando containers...${RESET}"
	@$(DOCKER_COMPOSE) stop
	@echo "${GREEN}✓ Containers parados!${RESET}"

down: ## Para e remove todos os containers
	@echo "${RED}➜ Removendo containers...${RESET}"
	@$(DOCKER_COMPOSE) down
	@echo "${GREEN}✓ Containers removidos!${RESET}"

restart: stop start ## Reinicia todos os containers

build: ## Reconstrói as imagens Docker
	@echo "${BLUE}➜ Construindo imagens...${RESET}"
	@$(DOCKER_COMPOSE) build
	@echo "${GREEN}✓ Imagens construídas!${RESET}"

rebuild: ## Reconstrói as imagens do zero (sem cache)
	@echo "${BLUE}➜ Reconstruindo imagens (sem cache)...${RESET}"
	@$(DOCKER_COMPOSE) build --no-cache
	@echo "${GREEN}✓ Imagens reconstruídas!${RESET}"

ps: ## Lista status dos containers
	@$(DOCKER_COMPOSE) ps

status: ps ## Alias para ps

##@ Logs e Monitoramento

logs: ## Mostra logs de todos os containers
	@$(DOCKER_COMPOSE) logs -f

logs-app: ## Mostra logs apenas da aplicação
	@$(DOCKER_COMPOSE) logs -f app

logs-db: ## Mostra logs apenas do MySQL
	@$(DOCKER_COMPOSE) logs -f database

logs-phpmyadmin: ## Mostra logs apenas do phpMyAdmin
	@$(DOCKER_COMPOSE) logs -f phpmyadmin

##@ Acesso aos Containers

shell: ## Acessa o shell do container da aplicação
	@echo "${BLUE}➜ Acessando container da aplicação...${RESET}"
	@$(EXEC_APP) bash

shell-db: ## Acessa o shell do container MySQL
	@echo "${BLUE}➜ Acessando container MySQL...${RESET}"
	@$(EXEC_DB) bash

mysql: ## Acessa o MySQL via CLI
	@echo "${BLUE}➜ Acessando MySQL CLI...${RESET}"
	@$(EXEC_DB) mysql -u root -proot_password ms_code_pdv_symfony

phpmyadmin: ## Abre o phpMyAdmin no navegador
	@echo "${GREEN}➜ Abrindo phpMyAdmin...${RESET}"
	@echo "${BLUE}URL: http://localhost:8081${RESET}"
	@xdg-open http://localhost:8081 2>/dev/null || open http://localhost:8081 2>/dev/null || echo "Acesse: http://localhost:8081"

app: ## Abre a aplicação no navegador
	@echo "${GREEN}➜ Abrindo aplicação...${RESET}"
	@echo "${BLUE}URL: http://localhost:8080${RESET}"
	@xdg-open http://localhost:8080 2>/dev/null || open http://localhost:8080 2>/dev/null || echo "Acesse: http://localhost:8080"

##@ Banco de Dados

migrate: ## Executa as migrations pendentes
	@echo "${BLUE}➜ Executando migrations...${RESET}"
	@$(EXEC_APP) php bin/console doctrine:migrations:migrate --no-interaction
	@echo "${GREEN}✓ Migrations executadas!${RESET}"

migrate-status: ## Mostra o status das migrations
	@$(EXEC_APP) php bin/console doctrine:migrations:status

migrate-diff: ## Gera uma nova migration baseada nas mudanças
	@echo "${BLUE}➜ Gerando migration...${RESET}"
	@$(EXEC_APP) php bin/console doctrine:migrations:diff
	@echo "${GREEN}✓ Migration gerada!${RESET}"

seed: ## Popula o banco com dados mockados
	@echo "${BLUE}➜ Populando banco de dados...${RESET}"
	@$(EXEC_APP) php bin/console app:popular-banco --no-interaction
	@echo "${GREEN}✓ Banco populado!${RESET}"

db-create: ## Cria o banco de dados
	@echo "${BLUE}➜ Criando banco de dados...${RESET}"
	@$(EXEC_APP) php bin/console doctrine:database:create
	@echo "${GREEN}✓ Banco criado!${RESET}"

db-drop: ## Remove o banco de dados (CUIDADO!)
	@echo "${RED}⚠ Removendo banco de dados...${RESET}"
	@$(EXEC_APP) php bin/console doctrine:database:drop --force
	@echo "${GREEN}✓ Banco removido!${RESET}"

db-reset: db-drop db-create migrate seed ## Reseta completamente o banco de dados

##@ Symfony Console

console: ## Acessa o console do Symfony
	@$(EXEC_APP) php bin/console

cache-clear: ## Limpa o cache da aplicação
	@echo "${BLUE}➜ Limpando cache...${RESET}"
	@$(EXEC_APP) php bin/console cache:clear
	@echo "${GREEN}✓ Cache limpo!${RESET}"

cache-warmup: ## Aquece o cache da aplicação
	@echo "${BLUE}➜ Aquecendo cache...${RESET}"
	@$(EXEC_APP) php bin/console cache:warmup
	@echo "${GREEN}✓ Cache aquecido!${RESET}"

routes: ## Lista todas as rotas
	@$(EXEC_APP) php bin/console debug:router

router: routes ## Alias para routes

controllers: ## Lista todos os controllers
	@$(EXEC_APP) php bin/console debug:container --tag=controller.service_arguments

create-user: ## Cria um novo usuário
	@$(EXEC_APP) php bin/console app:adicionar-usuario

create-permission: ## Cria uma nova permissão
	@$(EXEC_APP) php bin/console app:criar-permissoes

##@ Testes

test: ## Executa os testes
	@echo "${BLUE}➜ Executando testes...${RESET}"
	@$(EXEC_APP) php bin/phpunit
	@echo "${GREEN}✓ Testes concluídos!${RESET}"

test-coverage: ## Executa testes com cobertura
	@echo "${BLUE}➜ Executando testes com cobertura...${RESET}"
	@$(EXEC_APP) php bin/phpunit --coverage-html coverage
	@echo "${GREEN}✓ Relatório de cobertura gerado em coverage/index.html${RESET}"

##@ Limpeza

clean: ## Remove containers, volumes e cache
	@echo "${RED}➜ Limpando tudo...${RESET}"
	@$(DOCKER_COMPOSE) down -v
	@rm -rf var/cache/* var/log/*
	@echo "${GREEN}✓ Limpeza concluída!${RESET}"

clean-cache: ## Remove apenas o cache
	@echo "${YELLOW}➜ Removendo cache...${RESET}"
	@rm -rf var/cache/* var/log/*
	@echo "${GREEN}✓ Cache removido!${RESET}"

prune: ## Remove todos os recursos Docker não utilizados
	@echo "${RED}⚠ Removendo recursos Docker não utilizados...${RESET}"
	@docker system prune -af --volumes
	@echo "${GREEN}✓ Limpeza Docker concluída!${RESET}"

##@ Desenvolvimento

fix-permissions: ## Corrige permissões de arquivos
	@echo "${BLUE}➜ Corrigindo permissões...${RESET}"
	@$(EXEC_APP) chown -R www-data:www-data /var/www/html/var
	@$(EXEC_APP) chmod -R 775 /var/www/html/var
	@echo "${GREEN}✓ Permissões corrigidas!${RESET}"

composer-update: ## Atualiza as dependências do Composer
	@echo "${YELLOW}➜ Atualizando dependências...${RESET}"
	@$(EXEC_APP) composer update
	@echo "${GREEN}✓ Dependências atualizadas!${RESET}"

composer-require: ## Instala um novo pacote (use: make composer-require PACKAGE=nome/pacote)
	@echo "${BLUE}➜ Instalando pacote ${PACKAGE}...${RESET}"
	@$(EXEC_APP) composer require $(PACKAGE)
	@echo "${GREEN}✓ Pacote instalado!${RESET}"

dump-autoload: ## Atualiza o autoload do Composer
	@$(EXEC_APP) composer dump-autoload

assets-install: ## Instala os assets
	@echo "${BLUE}➜ Instalando assets...${RESET}"
	@$(EXEC_APP) php bin/console assets:install
	@$(EXEC_APP) php bin/console importmap:install
	@echo "${GREEN}✓ Assets instalados!${RESET}"

##@ Informações

info: ## Mostra informações do projeto
	@echo "${BLUE}╔══════════════════════════════════════════════════════════╗${RESET}"
	@echo "${BLUE}║       MS-CODE-PDV-SYMFONY - Informações                 ║${RESET}"
	@echo "${BLUE}╚══════════════════════════════════════════════════════════╝${RESET}"
	@echo ""
	@echo "${GREEN}📦 Containers:${RESET}"
	@$(DOCKER_COMPOSE) ps
	@echo ""
	@echo "${GREEN}🌐 URLs:${RESET}"
	@echo "  Aplicação:  ${BLUE}http://localhost:8080${RESET}"
	@echo "  phpMyAdmin: ${BLUE}http://localhost:8081${RESET}"
	@echo ""
	@echo "${GREEN}🔐 Credenciais:${RESET}"
	@echo "  Admin:      ${YELLOW}admin@admin.com / admin123${RESET}"
	@echo "  Gerente:    ${YELLOW}gerente@pdv.com / gerente123${RESET}"
	@echo "  Vendedor:   ${YELLOW}vendedor@pdv.com / vendedor123${RESET}"
	@echo ""
	@echo "${GREEN}💾 Banco de Dados:${RESET}"
	@echo "  Host:       ${YELLOW}localhost:3307${RESET}"
	@echo "  Database:   ${YELLOW}ms_code_pdv_symfony${RESET}"
	@echo "  User:       ${YELLOW}symfony_user${RESET}"
	@echo "  Password:   ${YELLOW}symfony_password${RESET}"
	@echo "  Root Pass:  ${YELLOW}root_password${RESET}"
	@echo ""

version: ## Mostra versões das ferramentas
	@echo "${BLUE}Versões:${RESET}"
	@echo "PHP: $$($(EXEC_APP) php -v | head -n 1)"
	@echo "Composer: $$($(EXEC_APP) composer -V)"
	@echo "Symfony: $$($(EXEC_APP) php bin/console --version)"
	@echo "MySQL: $$($(EXEC_DB) mysql -V)"

compile:
	@echo "${BLUE}➜ Compilando assets...${RESET}"
	@$(EXEC_APP) php bin/console asset-map:compile
	@echo "${GREEN}✓ Assets compilados!${RESET}"
