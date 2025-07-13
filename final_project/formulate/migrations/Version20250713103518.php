<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250713103518 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE salesforce_account DROP FOREIGN KEY FK_DB842773A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE salesforce_account ADD CONSTRAINT FK_DB842773A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE salesforce_account DROP FOREIGN KEY FK_DB842773A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE salesforce_account ADD CONSTRAINT FK_DB842773A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
    }
}
