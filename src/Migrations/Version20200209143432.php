<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200209143432 extends AbstractMigration
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
        $this->addSql('CREATE SEQUENCE calendrier_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE event_zap_pas_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE users (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('CREATE TABLE calendrier (id INT NOT NULL, nom VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, doc_persist_json JSON NOT NULL, classe TEXT NOT NULL, formateurs TEXT NOT NULL, admin TEXT NOT NULL, administratifs TEXT NOT NULL, event_zimbra TEXT NOT NULL, event_local TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN calendrier.classe IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN calendrier.formateurs IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN calendrier.admin IS \'(DC2Type:object)\'');
        $this->addSql('COMMENT ON COLUMN calendrier.administratifs IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN calendrier.event_zimbra IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN calendrier.event_local IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE event_zap_pas (id INT NOT NULL, nom VARCHAR(255) NOT NULL, lieu VARCHAR(255) NOT NULL, lien VARCHAR(255) NOT NULL, description TEXT NOT NULL, validation INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE cours_zimbra ADD email_intervenant VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE cours_zimbra ADD description_event VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE users_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE calendrier_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE event_zap_pas_id_seq CASCADE');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE calendrier');
        $this->addSql('DROP TABLE event_zap_pas');
        $this->addSql('ALTER TABLE cours_zimbra DROP email_intervenant');
        $this->addSql('ALTER TABLE cours_zimbra DROP description_event');
    }
}
