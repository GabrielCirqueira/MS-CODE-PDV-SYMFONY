<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241025224027 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE nome');
        $this->addSql('ALTER TABLE carrinho CHANGE atualizado_em atualizado_em DATETIME DEFAULT NULL, CHANGE finalizado_em finalizado_em DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE categoria CHANGE atualizado_em atualizado_em DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE produto CHANGE atualizado_em atualizado_em DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE nome (id INT AUTO_INCREMENT NOT NULL, quantidade INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE carrinho CHANGE atualizado_em atualizado_em DATETIME NOT NULL, CHANGE finalizado_em finalizado_em DATETIME NOT NULL');
        $this->addSql('ALTER TABLE categoria CHANGE atualizado_em atualizado_em DATETIME NOT NULL');
        $this->addSql('ALTER TABLE produto CHANGE atualizado_em atualizado_em DATETIME NOT NULL');
    }
}
