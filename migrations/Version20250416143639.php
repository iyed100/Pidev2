<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250416143639 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE personne
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE assurance DROP FOREIGN KEY FK_386829AE5ADA84A2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE assurance ADD CONSTRAINT FK_386829AE5ADA84A2 FOREIGN KEY (id_reservation) REFERENCES reservation (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE coworking_space DROP FOREIGN KEY FK_25E9F1AA3243BB18
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE coworking_space ADD CONSTRAINT FK_25E9F1AA3243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE location CHANGE latitude latitude NUMERIC(11, 8) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP FOREIGN KEY FK_42C849555E5C27E9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955D55632C0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495518B088DB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955FF77AD23
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD date_debut DATETIME NOT NULL, ADD date_fin DATETIME NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD CONSTRAINT FK_42C849555E5C27E9 FOREIGN KEY (iduser) REFERENCES utilisateur (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD CONSTRAINT FK_42C84955D55632C0 FOREIGN KEY (idhotel) REFERENCES hotel (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD CONSTRAINT FK_42C8495518B088DB FOREIGN KEY (idtransport) REFERENCES transport_means (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD CONSTRAINT FK_42C84955FF77AD23 FOREIGN KEY (idspace) REFERENCES coworking_space (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE route DROP FOREIGN KEY FK_2C420796DD33C2B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE route ADD CONSTRAINT FK_2C420796DD33C2B FOREIGN KEY (transportId) REFERENCES transport_means (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE utilisateur DROP telephone, CHANGE nom nom VARCHAR(100) NOT NULL, CHANGE prenom prenom VARCHAR(100) NOT NULL, CHANGE email email VARCHAR(180) NOT NULL, CHANGE role role VARCHAR(50) DEFAULT 'client' NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_1D1C63B3E7927C74 ON utilisateur (email)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE personne (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, prenom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, age INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE assurance DROP FOREIGN KEY FK_386829AE5ADA84A2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE assurance ADD CONSTRAINT FK_386829AE5ADA84A2 FOREIGN KEY (id_reservation) REFERENCES reservation (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE coworking_space DROP FOREIGN KEY FK_25E9F1AA3243BB18
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE coworking_space ADD CONSTRAINT FK_25E9F1AA3243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE location CHANGE latitude latitude NUMERIC(10, 8) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955D55632C0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955FF77AD23
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495518B088DB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP FOREIGN KEY FK_42C849555E5C27E9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP date_debut, DROP date_fin
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD CONSTRAINT FK_42C84955D55632C0 FOREIGN KEY (idhotel) REFERENCES hotel (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD CONSTRAINT FK_42C84955FF77AD23 FOREIGN KEY (idspace) REFERENCES coworking_space (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD CONSTRAINT FK_42C8495518B088DB FOREIGN KEY (idtransport) REFERENCES transport_means (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD CONSTRAINT FK_42C849555E5C27E9 FOREIGN KEY (iduser) REFERENCES utilisateur (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE route DROP FOREIGN KEY FK_2C420796DD33C2B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE route ADD CONSTRAINT FK_2C420796DD33C2B FOREIGN KEY (transportId) REFERENCES transport_means (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_1D1C63B3E7927C74 ON utilisateur
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE utilisateur ADD telephone VARCHAR(255) NOT NULL, CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE prenom prenom VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE role role VARCHAR(255) NOT NULL
        SQL);
    }
}
