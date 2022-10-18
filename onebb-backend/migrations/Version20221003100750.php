<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221003100750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, om_id INT NOT NULL, sender_id INT NOT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_B6BD307F8D0C247D (om_id), UNIQUE INDEX UNIQ_B6BD307FF624B39D (sender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE one_messenger (id INT AUTO_INCREMENT NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE one_messenger_user (one_messenger_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_3139287DCE6043E4 (one_messenger_id), INDEX IDX_3139287DA76ED395 (user_id), PRIMARY KEY(one_messenger_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F8D0C247D FOREIGN KEY (om_id) REFERENCES one_messenger (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE one_messenger_user ADD CONSTRAINT FK_3139287DCE6043E4 FOREIGN KEY (one_messenger_id) REFERENCES one_messenger (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE one_messenger_user ADD CONSTRAINT FK_3139287DA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F8D0C247D');
        $this->addSql('ALTER TABLE one_messenger_user DROP FOREIGN KEY FK_3139287DCE6043E4');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE one_messenger');
        $this->addSql('DROP TABLE one_messenger_user');
        $this->addSql('ALTER TABLE `user` CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
