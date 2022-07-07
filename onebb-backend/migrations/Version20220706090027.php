<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220706090027 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE metafield ADD plugin_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE metafield ADD CONSTRAINT FK_95764600EC942BCF FOREIGN KEY (plugin_id) REFERENCES plugin (id)');
        $this->addSql('CREATE INDEX IDX_95764600EC942BCF ON metafield (plugin_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE metafield DROP FOREIGN KEY FK_95764600EC942BCF');
        $this->addSql('DROP INDEX IDX_95764600EC942BCF ON metafield');
        $this->addSql('ALTER TABLE metafield DROP plugin_id');
    }
}
