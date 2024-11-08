<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241104220429 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE carrinho (id INT AUTO_INCREMENT NOT NULL, cliente_id_id INT DEFAULT NULL, usuario_id_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, valor_total INT NOT NULL, criado_em DATETIME NOT NULL, atualizado_em DATETIME DEFAULT NULL, finalizado_em DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_A731E3C0ACC9C364 (cliente_id_id), INDEX IDX_A731E3C0629AF449 (usuario_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categoria (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, criado_em DATETIME NOT NULL, atualizado_em DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cliente (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, cpf VARCHAR(14) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, quatidade INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_carrinho (item_id INT NOT NULL, carrinho_id INT NOT NULL, INDEX IDX_CB3C2B0B126F525E (item_id), INDEX IDX_CB3C2B0BD363F3C2 (carrinho_id), PRIMARY KEY(item_id, carrinho_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produto (id INT AUTO_INCREMENT NOT NULL, categoria_id_id INT DEFAULT NULL, item_id INT DEFAULT NULL, nome VARCHAR(255) NOT NULL, descricao VARCHAR(255) DEFAULT NULL, quantidade INT NOT NULL, valor_unitario INT NOT NULL, criado_em DATETIME NOT NULL, atualizado_em DATETIME DEFAULT NULL, INDEX IDX_5CAC49D77E735794 (categoria_id_id), INDEX IDX_5CAC49D7126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nome VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vendas (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(255) NOT NULL, data DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE carrinho ADD CONSTRAINT FK_A731E3C0ACC9C364 FOREIGN KEY (cliente_id_id) REFERENCES cliente (id)');
        $this->addSql('ALTER TABLE carrinho ADD CONSTRAINT FK_A731E3C0629AF449 FOREIGN KEY (usuario_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE item_carrinho ADD CONSTRAINT FK_CB3C2B0B126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_carrinho ADD CONSTRAINT FK_CB3C2B0BD363F3C2 FOREIGN KEY (carrinho_id) REFERENCES carrinho (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produto ADD CONSTRAINT FK_5CAC49D77E735794 FOREIGN KEY (categoria_id_id) REFERENCES categoria (id)');
        $this->addSql('ALTER TABLE produto ADD CONSTRAINT FK_5CAC49D7126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carrinho DROP FOREIGN KEY FK_A731E3C0ACC9C364');
        $this->addSql('ALTER TABLE carrinho DROP FOREIGN KEY FK_A731E3C0629AF449');
        $this->addSql('ALTER TABLE item_carrinho DROP FOREIGN KEY FK_CB3C2B0B126F525E');
        $this->addSql('ALTER TABLE item_carrinho DROP FOREIGN KEY FK_CB3C2B0BD363F3C2');
        $this->addSql('ALTER TABLE produto DROP FOREIGN KEY FK_5CAC49D77E735794');
        $this->addSql('ALTER TABLE produto DROP FOREIGN KEY FK_5CAC49D7126F525E');
        $this->addSql('DROP TABLE carrinho');
        $this->addSql('DROP TABLE categoria');
        $this->addSql('DROP TABLE cliente');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE item_carrinho');
        $this->addSql('DROP TABLE produto');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE vendas');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
