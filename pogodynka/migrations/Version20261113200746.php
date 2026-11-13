<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261113200746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__forecast AS SELECT id, date, temperature, predicted_rainfall, humidity, wind, place_id FROM forecast');
        $this->addSql('DROP TABLE forecast');
        $this->addSql('CREATE TABLE forecast (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date DATE NOT NULL, temperature DOUBLE PRECISION NOT NULL, predicted_rainfall INTEGER NOT NULL, humidity INTEGER NOT NULL, wind INTEGER NOT NULL, place_id INTEGER NOT NULL, CONSTRAINT FK_2A9C7844DA6A219 FOREIGN KEY (place_id) REFERENCES place (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO forecast (id, date, temperature, predicted_rainfall, humidity, wind, place_id) SELECT id, date, temperature, predicted_rainfall, humidity, wind, place_id FROM __temp__forecast');
        $this->addSql('DROP TABLE __temp__forecast');
        $this->addSql('CREATE INDEX IDX_2A9C7844DA6A219 ON forecast (place_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__forecast AS SELECT id, date, temperature, predicted_rainfall, humidity, wind, place_id FROM forecast');
        $this->addSql('DROP TABLE forecast');
        $this->addSql('CREATE TABLE forecast (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date DATE NOT NULL, temperature INTEGER NOT NULL, predicted_rainfall INTEGER NOT NULL, humidity INTEGER NOT NULL, wind INTEGER NOT NULL, place_id INTEGER NOT NULL, CONSTRAINT FK_2A9C7844DA6A219 FOREIGN KEY (place_id) REFERENCES place (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO forecast (id, date, temperature, predicted_rainfall, humidity, wind, place_id) SELECT id, date, temperature, predicted_rainfall, humidity, wind, place_id FROM __temp__forecast');
        $this->addSql('DROP TABLE __temp__forecast');
        $this->addSql('CREATE INDEX IDX_2A9C7844DA6A219 ON forecast (place_id)');
    }
}
