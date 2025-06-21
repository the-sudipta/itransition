<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250621233153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, choosen_option_id INT DEFAULT NULL, form_submit_id INT NOT NULL, answer_text LONGTEXT NOT NULL, INDEX IDX_DADD4A251E27F6BF (question_id), INDEX IDX_DADD4A25D3823036 (choosen_option_id), INDEX IDX_DADD4A25FBABB4DB (form_submit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, template_id INT NOT NULL, user_id INT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_9474526C5DA0FB8 (template_id), INDEX IDX_9474526CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE form_submit (id INT AUTO_INCREMENT NOT NULL, template_id INT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_3DDF67ED5DA0FB8 (template_id), INDEX IDX_3DDF67EDA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `like` (id INT AUTO_INCREMENT NOT NULL, template_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_AC6340B35DA0FB8 (template_id), INDEX IDX_AC6340B3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `option` (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, text LONGTEXT NOT NULL, INDEX IDX_5A8600B01E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, template_id INT NOT NULL, type VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, show_in_results TINYINT(1) NOT NULL, position VARCHAR(255) NOT NULL, INDEX IDX_B6F7494E5DA0FB8 (template_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE template (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, topic VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, is_public TINYINT(1) NOT NULL, version VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, last_updated_at DATETIME NOT NULL, INDEX IDX_97601F83A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE template_tag (id INT AUTO_INCREMENT NOT NULL, template_id INT NOT NULL, tag VARCHAR(255) NOT NULL, INDEX IDX_ADE23EA15DA0FB8 (template_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE answer ADD CONSTRAINT FK_DADD4A25D3823036 FOREIGN KEY (choosen_option_id) REFERENCES `option` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE answer ADD CONSTRAINT FK_DADD4A25FBABB4DB FOREIGN KEY (form_submit_id) REFERENCES form_submit (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment ADD CONSTRAINT FK_9474526C5DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form_submit ADD CONSTRAINT FK_3DDF67ED5DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form_submit ADD CONSTRAINT FK_3DDF67EDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B35DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `option` ADD CONSTRAINT FK_5A8600B01E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE question ADD CONSTRAINT FK_B6F7494E5DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE template ADD CONSTRAINT FK_97601F83A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE template_tag ADD CONSTRAINT FK_ADE23EA15DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A251E27F6BF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A25D3823036
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A25FBABB4DB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment DROP FOREIGN KEY FK_9474526C5DA0FB8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form_submit DROP FOREIGN KEY FK_3DDF67ED5DA0FB8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE form_submit DROP FOREIGN KEY FK_3DDF67EDA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B35DA0FB8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `option` DROP FOREIGN KEY FK_5A8600B01E27F6BF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E5DA0FB8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE template DROP FOREIGN KEY FK_97601F83A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE template_tag DROP FOREIGN KEY FK_ADE23EA15DA0FB8
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE answer
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE comment
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE form_submit
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `like`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `option`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE question
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE template
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE template_tag
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
    }
}
