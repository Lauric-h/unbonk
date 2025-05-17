<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250517213451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds VO to Race and CP';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE checkpoint ADD metrics_from_start_estimated_time_in_minutes_minutes INT NOT NULL, ADD metrics_from_start_distance_value INT NOT NULL, ADD metrics_from_start_ascent_value INT NOT NULL, ADD metrics_from_start_descent_value INT NOT NULL, DROP metrics_from_start_estimated_time_in_minutes, DROP metrics_from_start_distance, DROP metrics_from_start_elevation_gain, DROP metrics_from_start_elevation_loss');
        $this->addSql('ALTER TABLE race ADD profile_distance_value INT NOT NULL, ADD profile_ascent_value INT NOT NULL, ADD profile_descent_value INT NOT NULL, DROP profile_distance, DROP profile_elevation_gain, DROP profile_elevation_loss');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE checkpoint ADD metrics_from_start_estimated_time_in_minutes INT NOT NULL, ADD metrics_from_start_distance INT NOT NULL, ADD metrics_from_start_elevation_gain INT NOT NULL, ADD metrics_from_start_elevation_loss INT NOT NULL, DROP metrics_from_start_estimated_time_in_minutes_minutes, DROP metrics_from_start_distance_value, DROP metrics_from_start_ascent_value, DROP metrics_from_start_descent_value');
        $this->addSql('ALTER TABLE race ADD profile_distance INT NOT NULL, ADD profile_elevation_gain INT NOT NULL, ADD profile_elevation_loss INT NOT NULL, DROP profile_distance_value, DROP profile_ascent_value, DROP profile_descent_value');
    }
}
