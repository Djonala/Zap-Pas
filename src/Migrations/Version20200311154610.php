<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200311154610 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE users_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE event_zap_pas_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE calendrier_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE event_zimbra_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE users (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, reset_token VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('CREATE TABLE event_zap_pas (id INT NOT NULL, nom VARCHAR(255) NOT NULL, lieu VARCHAR(255) NOT NULL, lien VARCHAR(255) NOT NULL, description TEXT NOT NULL, validation INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE calendrier (id INT NOT NULL, nom VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, classe TEXT NOT NULL, formateurs TEXT NOT NULL, admin TEXT NOT NULL, administratifs TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN calendrier.classe IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN calendrier.formateurs IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN calendrier.admin IS \'(DC2Type:object)\'');
        $this->addSql('COMMENT ON COLUMN calendrier.administratifs IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE event_zimbra (id INT NOT NULL, calendrier_id INT DEFAULT NULL, matiere VARCHAR(255) NOT NULL, nom_formateur VARCHAR(255) NOT NULL, lieu VARCHAR(255) NOT NULL, email_intervenant VARCHAR(255) NOT NULL, date_debut_event TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_fin_event TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_18639740FF52FC51 ON event_zimbra (calendrier_id)');
        $this->addSql('ALTER TABLE event_zimbra ADD CONSTRAINT FK_18639740FF52FC51 FOREIGN KEY (calendrier_id) REFERENCES calendrier (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE event_zimbra DROP CONSTRAINT FK_18639740FF52FC51');
        $this->addSql('DROP SEQUENCE users_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE event_zap_pas_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE calendrier_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE event_zimbra_id_seq CASCADE');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE event_zap_pas');
        $this->addSql('DROP TABLE calendrier');
        $this->addSql('DROP TABLE event_zimbra');
    }
}
