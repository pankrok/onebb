<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220609064526 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE board (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, last_active_user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, active TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', slug VARCHAR(255) NOT NULL, priority INT DEFAULT NULL, plots_no INT DEFAULT NULL, posts_no INT DEFAULT NULL, INDEX IDX_58562B4712469DE2 (category_id), INDEX IDX_58562B474A25660F (last_active_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE board_board (board_source INT NOT NULL, board_target INT NOT NULL, INDEX IDX_FAEBCC6CF3943BD4 (board_source), INDEX IDX_FAEBCC6CEA716B5B (board_target), PRIMARY KEY(board_source, board_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE box (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, engine VARCHAR(16) NOT NULL, html LONGTEXT DEFAULT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, active TINYINT(1) DEFAULT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', slug VARCHAR(255) NOT NULL, priority INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `group` (id INT AUTO_INCREMENT NOT NULL, html_code VARCHAR(255) NOT NULL, group_name VARCHAR(255) NOT NULL, group_level INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, slug VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', priority INT DEFAULT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plot (id INT AUTO_INCREMENT NOT NULL, board_id INT NOT NULL, user_id INT DEFAULT NULL, last_active_user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, tags VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, pinned TINYINT(1) DEFAULT NULL, pinned_order INT DEFAULT NULL, locked TINYINT(1) NOT NULL, hidden TINYINT(1) NOT NULL, views INT DEFAULT NULL, stars DOUBLE PRECISION DEFAULT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', slug VARCHAR(255) NOT NULL, post_no INT DEFAULT NULL, INDEX IDX_BEBB8F89E7EC5785 (board_id), INDEX IDX_BEBB8F89A76ED395 (user_id), INDEX IDX_BEBB8F894A25660F (last_active_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, plot_id INT NOT NULL, content LONGTEXT NOT NULL, reputation INT DEFAULT NULL, hidden TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_5A8A6C8DA76ED395 (user_id), INDEX IDX_5A8A6C8D680D0B01 (plot_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_user (post_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_44C6B1424B89032C (post_id), INDEX IDX_44C6B142A76ED395 (user_id), PRIMARY KEY(post_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refresh_tokens (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE skin (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, version VARCHAR(8) NOT NULL, author VARCHAR(32) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE skin_boxes (id INT AUTO_INCREMENT NOT NULL, skin_id INT NOT NULL, box_id INT NOT NULL, page VARCHAR(16) NOT NULL, position VARCHAR(16) NOT NULL, active TINYINT(1) NOT NULL, INDEX IDX_C2E43A6AF404637F (skin_id), INDEX IDX_C2E43A6AD8177B3F (box_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, user_group_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, banned TINYINT(1) NOT NULL, verified TINYINT(1) NOT NULL, acp_enable TINYINT(1) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) NOT NULL, posts_no INT NOT NULL, plots_no INT NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D6491ED93D47 (user_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_validation (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, selector VARCHAR(6) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', hash VARCHAR(32) NOT NULL, UNIQUE INDEX UNIQ_B1CB9D82A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE board ADD CONSTRAINT FK_58562B4712469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE board ADD CONSTRAINT FK_58562B474A25660F FOREIGN KEY (last_active_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE board_board ADD CONSTRAINT FK_FAEBCC6CF3943BD4 FOREIGN KEY (board_source) REFERENCES board (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE board_board ADD CONSTRAINT FK_FAEBCC6CEA716B5B FOREIGN KEY (board_target) REFERENCES board (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plot ADD CONSTRAINT FK_BEBB8F89E7EC5785 FOREIGN KEY (board_id) REFERENCES board (id)');
        $this->addSql('ALTER TABLE plot ADD CONSTRAINT FK_BEBB8F89A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE plot ADD CONSTRAINT FK_BEBB8F894A25660F FOREIGN KEY (last_active_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D680D0B01 FOREIGN KEY (plot_id) REFERENCES plot (id)');
        $this->addSql('ALTER TABLE post_user ADD CONSTRAINT FK_44C6B1424B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_user ADD CONSTRAINT FK_44C6B142A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE skin_boxes ADD CONSTRAINT FK_C2E43A6AF404637F FOREIGN KEY (skin_id) REFERENCES skin (id)');
        $this->addSql('ALTER TABLE skin_boxes ADD CONSTRAINT FK_C2E43A6AD8177B3F FOREIGN KEY (box_id) REFERENCES box (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D6491ED93D47 FOREIGN KEY (user_group_id) REFERENCES `group` (id)');
        $this->addSql('ALTER TABLE user_validation ADD CONSTRAINT FK_B1CB9D82A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE board_board DROP FOREIGN KEY FK_FAEBCC6CF3943BD4');
        $this->addSql('ALTER TABLE board_board DROP FOREIGN KEY FK_FAEBCC6CEA716B5B');
        $this->addSql('ALTER TABLE plot DROP FOREIGN KEY FK_BEBB8F89E7EC5785');
        $this->addSql('ALTER TABLE skin_boxes DROP FOREIGN KEY FK_C2E43A6AD8177B3F');
        $this->addSql('ALTER TABLE board DROP FOREIGN KEY FK_58562B4712469DE2');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6491ED93D47');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D680D0B01');
        $this->addSql('ALTER TABLE post_user DROP FOREIGN KEY FK_44C6B1424B89032C');
        $this->addSql('ALTER TABLE skin_boxes DROP FOREIGN KEY FK_C2E43A6AF404637F');
        $this->addSql('ALTER TABLE board DROP FOREIGN KEY FK_58562B474A25660F');
        $this->addSql('ALTER TABLE plot DROP FOREIGN KEY FK_BEBB8F89A76ED395');
        $this->addSql('ALTER TABLE plot DROP FOREIGN KEY FK_BEBB8F894A25660F');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('ALTER TABLE post_user DROP FOREIGN KEY FK_44C6B142A76ED395');
        $this->addSql('ALTER TABLE user_validation DROP FOREIGN KEY FK_B1CB9D82A76ED395');
        $this->addSql('DROP TABLE board');
        $this->addSql('DROP TABLE board_board');
        $this->addSql('DROP TABLE box');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE `group`');
        $this->addSql('DROP TABLE page');
        $this->addSql('DROP TABLE plot');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE post_user');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE skin');
        $this->addSql('DROP TABLE skin_boxes');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE user_validation');
    }
}
