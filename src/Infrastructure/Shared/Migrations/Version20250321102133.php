<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250321102133 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add checkpoints to Race';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE checkpoint (id VARCHAR(255) NOT NULL, race_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, checkpoint_type VARCHAR(255) NOT NULL, metrics_from_start_estimated_time_in_minutes INT NOT NULL, metrics_from_start_distance INT NOT NULL, metrics_from_start_elevation_gain INT NOT NULL, metrics_from_start_elevation_loss INT NOT NULL, INDEX IDX_F00F7BE6E59D40D (race_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE checkpoint ADD CONSTRAINT FK_F00F7BE6E59D40D FOREIGN KEY (race_id) REFERENCES race (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE checkpoint DROP FOREIGN KEY FK_F00F7BE6E59D40D');
        $this->addSql('DROP TABLE checkpoint');
    }
}
