<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200308105828 extends AbstractMigration
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
        $this->addSql('DROP TABLE cours_zimbra');
        $this->addSql('ALTER TABLE event_zimbra ADD calendrier_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event_zimbra ADD CONSTRAINT FK_18639740FF52FC51 FOREIGN KEY (calendrier_id) REFERENCES calendrier (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_18639740FF52FC51 ON event_zimbra (calendrier_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE cours_zimbra_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE cours_zimbra (id INT NOT NULL, matiere VARCHAR(255) NOT NULL, date DATE NOT NULL, heure_debut TIME(0) WITHOUT TIME ZONE NOT NULL, heure_fin TIME(0) WITHOUT TIME ZONE NOT NULL, nom_formateur VARCHAR(255) NOT NULL, lieu VARCHAR(255) NOT NULL, email_intervenant VARCHAR(255) NOT NULL, description_event VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE event_zimbra DROP CONSTRAINT FK_18639740FF52FC51');
        $this->addSql('DROP INDEX IDX_18639740FF52FC51');
        $this->addSql('ALTER TABLE event_zimbra DROP calendrier_id');
    }
}
