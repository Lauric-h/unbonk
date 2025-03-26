<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250326124536 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Model for NutritionPlan, NutritionItem and Segment.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE nutrition_item (id VARCHAR(255) NOT NULL, segment_id VARCHAR(255) NOT NULL, external_reference VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, carbs_value INT NOT NULL, quantity_value INT NOT NULL, calories_value INT NOT NULL, INDEX IDX_3421CB8DDB296AAD (segment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nutrition_plan (id VARCHAR(255) NOT NULL, race_id VARCHAR(255) NOT NULL, runner_id VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE segment (id VARCHAR(255) NOT NULL, nutrition_plan_id VARCHAR(255) NOT NULL, start_id VARCHAR(255) NOT NULL, finish_id VARCHAR(255) NOT NULL, distance_value INT NOT NULL, ascent_value INT NOT NULL, descent_value INT NOT NULL, estimated_time_in_minutes_minutes INT NOT NULL, carbs_target_value INT NOT NULL, INDEX IDX_1881F565113D03C9 (nutrition_plan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE nutrition_item ADD CONSTRAINT FK_3421CB8DDB296AAD FOREIGN KEY (segment_id) REFERENCES segment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE segment ADD CONSTRAINT FK_1881F565113D03C9 FOREIGN KEY (nutrition_plan_id) REFERENCES nutrition_plan (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE nutrition_item DROP FOREIGN KEY FK_3421CB8DDB296AAD');
        $this->addSql('ALTER TABLE segment DROP FOREIGN KEY FK_1881F565113D03C9');
        $this->addSql('DROP TABLE nutrition_item');
        $this->addSql('DROP TABLE nutrition_plan');
        $this->addSql('DROP TABLE segment');
    }
}
