<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250412230022 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE response (id INT AUTO_INCREMENT NOT NULL, claim_id INT DEFAULT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_3E7B0BFB7096A49F (claim_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE response ADD CONSTRAINT FK_3E7B0BFB7096A49F FOREIGN KEY (claim_id) REFERENCES claim (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE claim CHANGE status status VARCHAR(255) DEFAULT 'Pending' NOT NULL, CHANGE description description LONGTEXT NOT NULL, CHANGE Cdate Cdate DATE NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE response DROP FOREIGN KEY FK_3E7B0BFB7096A49F
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE response
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE claim CHANGE status status VARCHAR(255) DEFAULT '''Pending''' NOT NULL, CHANGE description description TEXT NOT NULL, CHANGE Cdate Cdate DATE DEFAULT 'current_timestamp()' NOT NULL
        SQL);
    }
}
