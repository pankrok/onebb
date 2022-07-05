<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220629084457 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE metafield (id INT AUTO_INCREMENT NOT NULL, namespace VARCHAR(255) NOT NULL, metafield_key VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE metafield_value (id INT AUTO_INCREMENT NOT NULL, metafield_id INT DEFAULT NULL, value LONGBLOB DEFAULT NULL, INDEX IDX_F5709C1A7904F92F (metafield_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE metafield_value ADD CONSTRAINT FK_F5709C1A7904F92F FOREIGN KEY (metafield_id) REFERENCES metafield (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE metafield_value DROP FOREIGN KEY FK_F5709C1A7904F92F');
        $this->addSql('DROP TABLE metafield');
        $this->addSql('DROP TABLE metafield_value');
    }
}
