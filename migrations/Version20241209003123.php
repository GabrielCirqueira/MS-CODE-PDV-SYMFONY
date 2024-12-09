<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241209003123 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE permissao (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, descricao VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_5BC84E4A54BD530C (nome), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_permissao (user_id INT NOT NULL, permissao_id INT NOT NULL, INDEX IDX_5B28C3AEA76ED395 (user_id), INDEX IDX_5B28C3AEE009E574 (permissao_id), PRIMARY KEY(user_id, permissao_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_permissao ADD CONSTRAINT FK_5B28C3AEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_permissao ADD CONSTRAINT FK_5B28C3AEE009E574 FOREIGN KEY (permissao_id) REFERENCES permissao (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_permissao DROP FOREIGN KEY FK_5B28C3AEA76ED395');
        $this->addSql('ALTER TABLE user_permissao DROP FOREIGN KEY FK_5B28C3AEE009E574');
        $this->addSql('DROP TABLE permissao');
        $this->addSql('DROP TABLE user_permissao');
    }
}
