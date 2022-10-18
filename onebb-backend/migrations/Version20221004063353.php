<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221004063353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP INDEX UNIQ_B6BD307F8D0C247D, ADD INDEX IDX_B6BD307F8D0C247D (om_id)');
        $this->addSql('ALTER TABLE message DROP INDEX UNIQ_B6BD307FF624B39D, ADD INDEX IDX_B6BD307FF624B39D (sender_id)');
        $this->addSql('ALTER TABLE message CHANGE sender_id sender_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP INDEX IDX_B6BD307F8D0C247D, ADD UNIQUE INDEX UNIQ_B6BD307F8D0C247D (om_id)');
        $this->addSql('ALTER TABLE message DROP INDEX IDX_B6BD307FF624B39D, ADD UNIQUE INDEX UNIQ_B6BD307FF624B39D (sender_id)');
        $this->addSql('ALTER TABLE message CHANGE sender_id sender_id INT NOT NULL');
    }
}
