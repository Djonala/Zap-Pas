<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200130150728 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE event_zap_pas_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE cours_zimbra_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE event_zap_pas (id INT NOT NULL, nom VARCHAR(255) NOT NULL, lieu VARCHAR(255) NOT NULL, lien VARCHAR(255) NOT NULL, description TEXT NOT NULL, validation INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE cours_zimbra (id INT NOT NULL, matiere VARCHAR(255) NOT NULL, date DATE NOT NULL, heure_debut TIME(0) WITHOUT TIME ZONE NOT NULL, heure_fin TIME(0) WITHOUT TIME ZONE NOT NULL, nom_formateur VARCHAR(255) NOT NULL, lieu VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE event_zap_pas_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE cours_zimbra_id_seq CASCADE');
        $this->addSql('DROP TABLE event_zap_pas');
        $this->addSql('DROP TABLE cours_zimbra');
    }
}
