<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241127151910 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item_carrinho DROP FOREIGN KEY FK_CB3C2B0B126F525E');
        $this->addSql('ALTER TABLE item_carrinho DROP FOREIGN KEY FK_CB3C2B0BD363F3C2');
        $this->addSql('DROP TABLE item_carrinho');
        $this->addSql('ALTER TABLE carrinho DROP INDEX UNIQ_A731E3C0DE734E51, ADD INDEX IDX_A731E3C0DE734E51 (cliente_id)');
        $this->addSql('ALTER TABLE item ADD carrinho_id INT NOT NULL, ADD quantidade INT NOT NULL, ADD valor INT NOT NULL, CHANGE quatidade produto_id INT NOT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E105CFD56 FOREIGN KEY (produto_id) REFERENCES produto (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251ED363F3C2 FOREIGN KEY (carrinho_id) REFERENCES carrinho (id)');
        $this->addSql('CREATE INDEX IDX_1F1B251E105CFD56 ON item (produto_id)');
        $this->addSql('CREATE INDEX IDX_1F1B251ED363F3C2 ON item (carrinho_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item_carrinho (item_id INT NOT NULL, carrinho_id INT NOT NULL, INDEX IDX_CB3C2B0B126F525E (item_id), INDEX IDX_CB3C2B0BD363F3C2 (carrinho_id), PRIMARY KEY(item_id, carrinho_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE item_carrinho ADD CONSTRAINT FK_CB3C2B0B126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_carrinho ADD CONSTRAINT FK_CB3C2B0BD363F3C2 FOREIGN KEY (carrinho_id) REFERENCES carrinho (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE carrinho DROP INDEX IDX_A731E3C0DE734E51, ADD UNIQUE INDEX UNIQ_A731E3C0DE734E51 (cliente_id)');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E105CFD56');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251ED363F3C2');
        $this->addSql('DROP INDEX IDX_1F1B251E105CFD56 ON item');
        $this->addSql('DROP INDEX IDX_1F1B251ED363F3C2 ON item');
        $this->addSql('ALTER TABLE item ADD quatidade INT NOT NULL, DROP produto_id, DROP carrinho_id, DROP quantidade, DROP valor');
    }
}
