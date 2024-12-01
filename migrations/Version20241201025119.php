<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241201025119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item_carrinho (item_id INT NOT NULL, carrinho_id INT NOT NULL, INDEX IDX_CB3C2B0B126F525E (item_id), INDEX IDX_CB3C2B0BD363F3C2 (carrinho_id), PRIMARY KEY(item_id, carrinho_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item_carrinho ADD CONSTRAINT FK_CB3C2B0B126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_carrinho ADD CONSTRAINT FK_CB3C2B0BD363F3C2 FOREIGN KEY (carrinho_id) REFERENCES carrinho (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E105CFD56');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251ED363F3C2');
        $this->addSql('DROP INDEX IDX_1F1B251E105CFD56 ON item');
        $this->addSql('DROP INDEX IDX_1F1B251ED363F3C2 ON item');
        $this->addSql('ALTER TABLE item DROP produto_id, DROP carrinho_id');
        $this->addSql('ALTER TABLE produto DROP FOREIGN KEY FK_5CAC49D7126F525E');
        $this->addSql('DROP INDEX IDX_5CAC49D7126F525E ON produto');
        $this->addSql('ALTER TABLE produto DROP FOREIGN KEY FK_5CAC49D77E735794');
        $this->addSql('ALTER TABLE produto DROP item_id');
        $this->addSql('DROP INDEX idx_5cac49d77e735794 ON produto');
        $this->addSql('CREATE INDEX IDX_5CAC49D73397707A ON produto (categoria_id)');
        $this->addSql('ALTER TABLE produto ADD CONSTRAINT FK_5CAC49D77E735794 FOREIGN KEY (categoria_id) REFERENCES categoria (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item_carrinho DROP FOREIGN KEY FK_CB3C2B0B126F525E');
        $this->addSql('ALTER TABLE item_carrinho DROP FOREIGN KEY FK_CB3C2B0BD363F3C2');
        $this->addSql('DROP TABLE item_carrinho');
        $this->addSql('ALTER TABLE item ADD produto_id INT NOT NULL, ADD carrinho_id INT NOT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E105CFD56 FOREIGN KEY (produto_id) REFERENCES produto (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251ED363F3C2 FOREIGN KEY (carrinho_id) REFERENCES carrinho (id)');
        $this->addSql('CREATE INDEX IDX_1F1B251E105CFD56 ON item (produto_id)');
        $this->addSql('CREATE INDEX IDX_1F1B251ED363F3C2 ON item (carrinho_id)');
        $this->addSql('ALTER TABLE produto DROP FOREIGN KEY FK_5CAC49D73397707A');
        $this->addSql('ALTER TABLE produto ADD item_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produto ADD CONSTRAINT FK_5CAC49D7126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('CREATE INDEX IDX_5CAC49D7126F525E ON produto (item_id)');
        $this->addSql('DROP INDEX idx_5cac49d73397707a ON produto');
        $this->addSql('CREATE INDEX IDX_5CAC49D77E735794 ON produto (categoria_id)');
        $this->addSql('ALTER TABLE produto ADD CONSTRAINT FK_5CAC49D73397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id)');
    }
}
