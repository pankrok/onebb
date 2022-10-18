<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221014083404 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE board ADD meta_desc VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD meta_desc VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE plot ADD meta_desc VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE board DROP meta_desc');
        $this->addSql('ALTER TABLE category DROP meta_desc');
        $this->addSql('ALTER TABLE plot DROP meta_desc');
    }
}
