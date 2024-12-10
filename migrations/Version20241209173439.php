<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241209173439 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE carrinho (id INT AUTO_INCREMENT NOT NULL, cliente_id INT DEFAULT NULL, usuario_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, valor_total INT DEFAULT NULL, criado_em DATETIME NOT NULL, atualizado_em DATETIME DEFAULT NULL, finalizado_em DATETIME DEFAULT NULL, INDEX IDX_A731E3C0DE734E51 (cliente_id), INDEX IDX_A731E3C0DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categoria (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, criado_em DATETIME NOT NULL, atualizado_em DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cliente (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, cpf VARCHAR(14) NOT NULL, ativo TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, produto_id INT DEFAULT NULL, carrinho_id INT DEFAULT NULL, quantidade INT NOT NULL, valor INT NOT NULL, INDEX IDX_1F1B251E105CFD56 (produto_id), INDEX IDX_1F1B251ED363F3C2 (carrinho_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permissao (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, grupo VARCHAR(255) NOT NULL, descricao VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_5BC84E4A54BD530C (nome), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produto (id INT AUTO_INCREMENT NOT NULL, categoria_id INT DEFAULT NULL, nome VARCHAR(255) NOT NULL, descricao VARCHAR(255) DEFAULT NULL, quantidade INT NOT NULL, valor_unitario INT NOT NULL, criado_em DATETIME NOT NULL, atualizado_em DATETIME DEFAULT NULL, ativo TINYINT(1) NOT NULL, INDEX IDX_5CAC49D73397707A (categoria_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nome VARCHAR(255) NOT NULL, ativo TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_permissao (user_id INT NOT NULL, permissao_id INT NOT NULL, INDEX IDX_5B28C3AEA76ED395 (user_id), INDEX IDX_5B28C3AEE009E574 (permissao_id), PRIMARY KEY(user_id, permissao_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vendas (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(255) NOT NULL, data DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE carrinho ADD CONSTRAINT FK_A731E3C0DE734E51 FOREIGN KEY (cliente_id) REFERENCES cliente (id)');
        $this->addSql('ALTER TABLE carrinho ADD CONSTRAINT FK_A731E3C0DB38439E FOREIGN KEY (usuario_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E105CFD56 FOREIGN KEY (produto_id) REFERENCES produto (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251ED363F3C2 FOREIGN KEY (carrinho_id) REFERENCES carrinho (id)');
        $this->addSql('ALTER TABLE produto ADD CONSTRAINT FK_5CAC49D73397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id)');
        $this->addSql('ALTER TABLE user_permissao ADD CONSTRAINT FK_5B28C3AEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_permissao ADD CONSTRAINT FK_5B28C3AEE009E574 FOREIGN KEY (permissao_id) REFERENCES permissao (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carrinho DROP FOREIGN KEY FK_A731E3C0DE734E51');
        $this->addSql('ALTER TABLE carrinho DROP FOREIGN KEY FK_A731E3C0DB38439E');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E105CFD56');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251ED363F3C2');
        $this->addSql('ALTER TABLE produto DROP FOREIGN KEY FK_5CAC49D73397707A');
        $this->addSql('ALTER TABLE user_permissao DROP FOREIGN KEY FK_5B28C3AEA76ED395');
        $this->addSql('ALTER TABLE user_permissao DROP FOREIGN KEY FK_5B28C3AEE009E574');
        $this->addSql('DROP TABLE carrinho');
        $this->addSql('DROP TABLE categoria');
        $this->addSql('DROP TABLE cliente');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE permissao');
        $this->addSql('DROP TABLE produto');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_permissao');
        $this->addSql('DROP TABLE vendas');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
