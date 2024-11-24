<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241124035137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carrinho DROP FOREIGN KEY FK_A731E3C0629AF449');
        $this->addSql('ALTER TABLE carrinho DROP FOREIGN KEY FK_A731E3C0ACC9C364');
        $this->addSql('DROP INDEX UNIQ_A731E3C0ACC9C364 ON carrinho');
        $this->addSql('DROP INDEX IDX_A731E3C0629AF449 ON carrinho');
        $this->addSql('ALTER TABLE carrinho ADD cliente_id INT DEFAULT NULL, ADD usuario_id INT DEFAULT NULL, DROP cliente_id_id, DROP usuario_id_id');
        $this->addSql('ALTER TABLE carrinho ADD CONSTRAINT FK_A731E3C0DE734E51 FOREIGN KEY (cliente_id) REFERENCES cliente (id)');
        $this->addSql('ALTER TABLE carrinho ADD CONSTRAINT FK_A731E3C0DB38439E FOREIGN KEY (usuario_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A731E3C0DE734E51 ON carrinho (cliente_id)');
        $this->addSql('CREATE INDEX IDX_A731E3C0DB38439E ON carrinho (usuario_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carrinho DROP FOREIGN KEY FK_A731E3C0DE734E51');
        $this->addSql('ALTER TABLE carrinho DROP FOREIGN KEY FK_A731E3C0DB38439E');
        $this->addSql('DROP INDEX UNIQ_A731E3C0DE734E51 ON carrinho');
        $this->addSql('DROP INDEX IDX_A731E3C0DB38439E ON carrinho');
        $this->addSql('ALTER TABLE carrinho ADD cliente_id_id INT DEFAULT NULL, ADD usuario_id_id INT DEFAULT NULL, DROP cliente_id, DROP usuario_id');
        $this->addSql('ALTER TABLE carrinho ADD CONSTRAINT FK_A731E3C0629AF449 FOREIGN KEY (usuario_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE carrinho ADD CONSTRAINT FK_A731E3C0ACC9C364 FOREIGN KEY (cliente_id_id) REFERENCES cliente (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A731E3C0ACC9C364 ON carrinho (cliente_id_id)');
        $this->addSql('CREATE INDEX IDX_A731E3C0629AF449 ON carrinho (usuario_id_id)');
    }
}
