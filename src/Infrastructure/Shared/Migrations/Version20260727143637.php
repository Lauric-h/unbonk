<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260727143637 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add tables for /Race';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE checkpoints (id VARCHAR(36) NOT NULL, runner_race_id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, distance_from_start_in_meters INT NOT NULL, ascent_from_start INT NOT NULL, descent_from_start INT NOT NULL, assistance_allowed BOOLEAN NOT NULL, type VARCHAR(20) NOT NULL, cutoff_offset_in_minutes INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_37D8517728080DEE ON checkpoints (runner_race_id)');
        $this->addSql('CREATE TABLE runner_races (id VARCHAR(36) NOT NULL, runner_id VARCHAR(36) NOT NULL, source_race_id VARCHAR(64) NOT NULL, event_id VARCHAR(64) NOT NULL, event_name VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, distance_in_meters INT NOT NULL, ascent INT NOT NULL, descent INT NOT NULL, start_date_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, location VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN runner_races.start_date_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE segments (id VARCHAR(36) NOT NULL, runner_race_id VARCHAR(36) NOT NULL, from_checkpoint_id VARCHAR(36) NOT NULL, to_checkpoint_id VARCHAR(36) NOT NULL, position INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_26CEDB2928080DEE ON segments (runner_race_id)');
        $this->addSql('CREATE INDEX IDX_26CEDB29BB2860C8 ON segments (from_checkpoint_id)');
        $this->addSql('CREATE INDEX IDX_26CEDB297DCD6A03 ON segments (to_checkpoint_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_segment_race_position ON segments (runner_race_id, position)');
        $this->addSql('ALTER TABLE checkpoints ADD CONSTRAINT FK_37D8517728080DEE FOREIGN KEY (runner_race_id) REFERENCES runner_races (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE segments ADD CONSTRAINT FK_26CEDB2928080DEE FOREIGN KEY (runner_race_id) REFERENCES runner_races (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE segments ADD CONSTRAINT FK_26CEDB29BB2860C8 FOREIGN KEY (from_checkpoint_id) REFERENCES checkpoints (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE segments ADD CONSTRAINT FK_26CEDB297DCD6A03 FOREIGN KEY (to_checkpoint_id) REFERENCES checkpoints (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE imported_race ADD event_name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE checkpoints DROP CONSTRAINT FK_37D8517728080DEE');
        $this->addSql('ALTER TABLE segments DROP CONSTRAINT FK_26CEDB2928080DEE');
        $this->addSql('ALTER TABLE segments DROP CONSTRAINT FK_26CEDB29BB2860C8');
        $this->addSql('ALTER TABLE segments DROP CONSTRAINT FK_26CEDB297DCD6A03');
        $this->addSql('DROP TABLE checkpoints');
        $this->addSql('DROP TABLE runner_races');
        $this->addSql('DROP TABLE segments');
        $this->addSql('ALTER TABLE imported_race DROP event_name');
    }
}
