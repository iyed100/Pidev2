<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250427143537 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE route_transport (id INT AUTO_INCREMENT NOT NULL, depart VARCHAR(255) NOT NULL, arrivee VARCHAR(255) NOT NULL, distance DOUBLE PRECISION NOT NULL, duree VARCHAR(50) NOT NULL, transportId INT NOT NULL, INDEX IDX_CAE9F4266DD33C2B (transportId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE route_transport ADD CONSTRAINT FK_CAE9F4266DD33C2B FOREIGN KEY (transportId) REFERENCES transport_means (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE route DROP FOREIGN KEY route_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE route
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE assurance CHANGE id_reservation id_reservation INT DEFAULT NULL, CHANGE type type VARCHAR(255) DEFAULT NULL, CHANGE conditions conditions VARCHAR(255) DEFAULT NULL, CHANGE statut statut VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE assurance ADD CONSTRAINT FK_386829AE5ADA84A2 FOREIGN KEY (id_reservation) REFERENCES reservation (id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX id_reservation ON assurance
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_386829AE5ADA84A2 ON assurance (id_reservation)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis ADD user_id INT NOT NULL, ADD service_id INT NOT NULL, DROP userId, DROP serviceId
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE claim CHANGE description description LONGTEXT NOT NULL, CHANGE userId user_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE coworking_space DROP FOREIGN KEY coworking_space_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE coworking_space DROP FOREIGN KEY coworking_space_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE coworking_space ADD CONSTRAINT FK_25E9F1AA3243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX hotel_id ON coworking_space
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_25E9F1AA3243BB18 ON coworking_space (hotel_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE coworking_space ADD CONSTRAINT coworking_space_ibfk_1 FOREIGN KEY (hotel_id) REFERENCES hotel (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE location CHANGE name name VARCHAR(255) NOT NULL, CHANGE type type VARCHAR(255) NOT NULL, CHANGE latitude latitude NUMERIC(11, 8) NOT NULL, CHANGE longitude longitude NUMERIC(11, 8) NOT NULL, CHANGE description description LONGTEXT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD nbrnuit INT NOT NULL, ADD nbrheure INT NOT NULL, CHANGE idhotel idhotel INT NOT NULL, CHANGE idspace idspace INT NOT NULL, CHANGE idtransport idtransport INT NOT NULL, CHANGE iduser iduser INT NOT NULL, CHANGE typeservice typeservice VARCHAR(255) NOT NULL, CHANGE statut statut VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE transport_means CHANGE dateDepart dateDepart DATETIME NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE utilisateur DROP telephone, CHANGE email email VARCHAR(180) NOT NULL, CHANGE role role VARCHAR(50) DEFAULT 'client' NOT NULL, CHANGE created_at created_at DATETIME NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX email ON utilisateur
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_1D1C63B3E7927C74 ON utilisateur (email)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE route (id INT AUTO_INCREMENT NOT NULL, depart VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, arrivee VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, distance DOUBLE PRECISION NOT NULL, duree VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, transportId INT NOT NULL, INDEX transportId (transportId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE route ADD CONSTRAINT route_ibfk_1 FOREIGN KEY (transportId) REFERENCES transport_means (id) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE route_transport DROP FOREIGN KEY FK_CAE9F4266DD33C2B
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE route_transport
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE assurance DROP FOREIGN KEY FK_386829AE5ADA84A2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE assurance DROP FOREIGN KEY FK_386829AE5ADA84A2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE assurance CHANGE id_reservation id_reservation INT NOT NULL, CHANGE type type VARCHAR(50) NOT NULL, CHANGE conditions conditions TEXT DEFAULT NULL, CHANGE statut statut VARCHAR(255) DEFAULT 'Actif'
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_386829ae5ada84a2 ON assurance
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX id_reservation ON assurance (id_reservation)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE assurance ADD CONSTRAINT FK_386829AE5ADA84A2 FOREIGN KEY (id_reservation) REFERENCES reservation (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis ADD userId INT NOT NULL, ADD serviceId INT NOT NULL, DROP user_id, DROP service_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE claim CHANGE description description TEXT NOT NULL, CHANGE user_id userId INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE coworking_space DROP FOREIGN KEY FK_25E9F1AA3243BB18
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE coworking_space DROP FOREIGN KEY FK_25E9F1AA3243BB18
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE coworking_space ADD CONSTRAINT coworking_space_ibfk_1 FOREIGN KEY (hotel_id) REFERENCES hotel (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_25e9f1aa3243bb18 ON coworking_space
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX hotel_id ON coworking_space (hotel_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE coworking_space ADD CONSTRAINT FK_25E9F1AA3243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE location CHANGE name name VARCHAR(100) NOT NULL, CHANGE type type VARCHAR(50) NOT NULL, CHANGE latitude latitude DOUBLE PRECISION NOT NULL, CHANGE longitude longitude DOUBLE PRECISION NOT NULL, CHANGE description description TEXT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP nbrnuit, DROP nbrheure, CHANGE idhotel idhotel INT DEFAULT NULL, CHANGE idspace idspace INT DEFAULT NULL, CHANGE idtransport idtransport INT DEFAULT NULL, CHANGE iduser iduser INT DEFAULT NULL, CHANGE typeservice typeservice VARCHAR(100) DEFAULT NULL, CHANGE statut statut VARCHAR(100) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE transport_means CHANGE dateDepart dateDepart VARCHAR(55) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE utilisateur ADD telephone VARCHAR(20) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE role role VARCHAR(255) DEFAULT 'client' NOT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX uniq_1d1c63b3e7927c74 ON utilisateur
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX email ON utilisateur (email)
        SQL);
    }
}
