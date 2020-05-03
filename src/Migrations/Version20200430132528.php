<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200430132528 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE user_parameters_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE user_parameters (id INT NOT NULL, autorized_send_mail BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE users ADD parameters_id INT NOT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E988BD9C1F FOREIGN KEY (parameters_id) REFERENCES user_parameters (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E988BD9C1F ON users (parameters_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E988BD9C1F');
        $this->addSql('DROP SEQUENCE user_parameters_id_seq CASCADE');
        $this->addSql('DROP TABLE user_parameters');
        $this->addSql('DROP INDEX UNIQ_1483A5E988BD9C1F');
        $this->addSql('ALTER TABLE users DROP parameters_id');
    }
}
