<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250922083821 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE nutrition_item (id VARCHAR(255) NOT NULL, nutrition_segment_id VARCHAR(255) NOT NULL, external_reference VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, quantity_value INT NOT NULL, calories_value INT NOT NULL, INDEX IDX_3421CB8D7CB1C63A (nutrition_segment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nutrition_plan (id VARCHAR(255) NOT NULL, runner_id VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nutrition_segment (id VARCHAR(255) NOT NULL, nutrition_plan_id VARCHAR(255) NOT NULL, segment_position INT NOT NULL, carbs_target_value INT NOT NULL, INDEX IDX_E125F437113D03C9 (nutrition_plan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE segment (id INT AUTO_INCREMENT NOT NULL, start_id VARCHAR(255) NOT NULL, finish_id VARCHAR(255) NOT NULL, race_id VARCHAR(255) NOT NULL, position INT NOT NULL, distance_value INT NOT NULL, ascent_value INT NOT NULL, descent_value INT NOT NULL, estimated_time_in_minutes_minutes INT NOT NULL, INDEX IDX_1881F565623DF99B (start_id), INDEX IDX_1881F5652B4667EB (finish_id), INDEX IDX_1881F5656E59D40D (race_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE nutrition_item ADD CONSTRAINT FK_3421CB8D7CB1C63A FOREIGN KEY (nutrition_segment_id) REFERENCES nutrition_segment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE nutrition_segment ADD CONSTRAINT FK_E125F437113D03C9 FOREIGN KEY (nutrition_plan_id) REFERENCES nutrition_plan (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE segment ADD CONSTRAINT FK_1881F565623DF99B FOREIGN KEY (start_id) REFERENCES checkpoint (id)');
        $this->addSql('ALTER TABLE segment ADD CONSTRAINT FK_1881F5652B4667EB FOREIGN KEY (finish_id) REFERENCES checkpoint (id)');
        $this->addSql('ALTER TABLE segment ADD CONSTRAINT FK_1881F5656E59D40D FOREIGN KEY (race_id) REFERENCES race (id)');
        $this->addSql('ALTER TABLE race ADD nutrition_plan_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE race ADD CONSTRAINT FK_DA6FBBAF113D03C9 FOREIGN KEY (nutrition_plan_id) REFERENCES nutrition_plan (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DA6FBBAF113D03C9 ON race (nutrition_plan_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE race DROP FOREIGN KEY FK_DA6FBBAF113D03C9');
        $this->addSql('ALTER TABLE nutrition_item DROP FOREIGN KEY FK_3421CB8D7CB1C63A');
        $this->addSql('ALTER TABLE nutrition_segment DROP FOREIGN KEY FK_E125F437113D03C9');
        $this->addSql('ALTER TABLE segment DROP FOREIGN KEY FK_1881F565623DF99B');
        $this->addSql('ALTER TABLE segment DROP FOREIGN KEY FK_1881F5652B4667EB');
        $this->addSql('ALTER TABLE segment DROP FOREIGN KEY FK_1881F5656E59D40D');
        $this->addSql('DROP TABLE nutrition_item');
        $this->addSql('DROP TABLE nutrition_plan');
        $this->addSql('DROP TABLE nutrition_segment');
        $this->addSql('DROP TABLE segment');
        $this->addSql('DROP INDEX UNIQ_DA6FBBAF113D03C9 ON race');
        $this->addSql('ALTER TABLE race DROP nutrition_plan_id');
    }
}
