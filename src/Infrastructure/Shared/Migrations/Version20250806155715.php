<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250806155715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change Profile to int';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE race ADD profile_distance INT NOT NULL, ADD profile_ascent INT NOT NULL, ADD profile_descent INT NOT NULL, DROP profile_distance_value, DROP profile_ascent_value, DROP profile_descent_value');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE race ADD profile_distance_value INT NOT NULL, ADD profile_ascent_value INT NOT NULL, ADD profile_descent_value INT NOT NULL, DROP profile_distance, DROP profile_ascent, DROP profile_descent');
    }
}
