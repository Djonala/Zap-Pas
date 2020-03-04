<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200304210142 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE cours_zimbra_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE event_zimbra_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE event_zimbra (id INT NOT NULL, matiere VARCHAR(255) NOT NULL, nom_formateur VARCHAR(255) NOT NULL, lieu VARCHAR(255) NOT NULL, email_intervenant VARCHAR(255) NOT NULL, date_debut_event TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_fin_event TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE cours_zimbra');
        $this->addSql('ALTER TABLE calendrier DROP doc_persist_json');
        $this->addSql('ALTER TABLE calendrier DROP event_zimbra');
        $this->addSql('ALTER TABLE calendrier DROP event_local');
        $this->addSql('ALTER TABLE users ADD reset_token VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE users ALTER roles TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE users ALTER roles DROP DEFAULT');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE event_zimbra_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE cours_zimbra_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE cours_zimbra (id INT NOT NULL, matiere VARCHAR(255) NOT NULL, date DATE NOT NULL, heure_debut TIME(0) WITHOUT TIME ZONE NOT NULL, heure_fin TIME(0) WITHOUT TIME ZONE NOT NULL, nom_formateur VARCHAR(255) NOT NULL, lieu VARCHAR(255) NOT NULL, email_intervenant VARCHAR(255) NOT NULL, description_event VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE event_zimbra');
        $this->addSql('ALTER TABLE calendrier ADD doc_persist_json JSON NOT NULL');
        $this->addSql('ALTER TABLE calendrier ADD event_zimbra TEXT NOT NULL');
        $this->addSql('ALTER TABLE calendrier ADD event_local TEXT NOT NULL');
        $this->addSql('COMMENT ON COLUMN calendrier.event_zimbra IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN calendrier.event_local IS \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE users DROP reset_token');
        $this->addSql('ALTER TABLE users ALTER roles TYPE JSON');
        $this->addSql('ALTER TABLE users ALTER roles DROP DEFAULT');
    }
}
