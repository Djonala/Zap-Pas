<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200202203155 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE user_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE security_user_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE calendrier_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE users_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE calendrier (id INT NOT NULL, nom VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, doc_persist_json JSON NOT NULL, classe TEXT NOT NULL, formateurs TEXT NOT NULL, admin TEXT NOT NULL, administratifs TEXT NOT NULL, event_zimbra TEXT NOT NULL, event_local TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN calendrier.classe IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN calendrier.formateurs IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN calendrier.admin IS \'(DC2Type:object)\'');
        $this->addSql('COMMENT ON COLUMN calendrier.administratifs IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN calendrier.event_zimbra IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN calendrier.event_local IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE users (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE security_user');
        $this->addSql('ALTER TABLE cours_zimbra ADD email_intervenant VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE cours_zimbra ADD description_event VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE calendrier_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE users_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE security_user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE security_user (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_52825a88e7927c74 ON security_user (email)');
        $this->addSql('DROP TABLE calendrier');
        $this->addSql('DROP TABLE users');
        $this->addSql('ALTER TABLE cours_zimbra DROP email_intervenant');
        $this->addSql('ALTER TABLE cours_zimbra DROP description_event');
    }
}
