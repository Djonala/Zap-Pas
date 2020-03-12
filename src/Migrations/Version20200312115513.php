<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200312115513 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE calendrier DROP classe');
        $this->addSql('ALTER TABLE calendrier DROP formateurs');
        $this->addSql('ALTER TABLE calendrier DROP admin');
        $this->addSql('ALTER TABLE calendrier DROP administratifs');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE calendrier ADD classe TEXT NOT NULL');
        $this->addSql('ALTER TABLE calendrier ADD formateurs TEXT NOT NULL');
        $this->addSql('ALTER TABLE calendrier ADD admin TEXT NOT NULL');
        $this->addSql('ALTER TABLE calendrier ADD administratifs TEXT NOT NULL');
        $this->addSql('COMMENT ON COLUMN calendrier.classe IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN calendrier.formateurs IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN calendrier.admin IS \'(DC2Type:object)\'');
        $this->addSql('COMMENT ON COLUMN calendrier.administratifs IS \'(DC2Type:array)\'');
    }
}
