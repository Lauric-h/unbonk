<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260729082649 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE nutrition_items (id VARCHAR(36) NOT NULL, segment_nutrition_plan_id VARCHAR(36) NOT NULL, food_catalog_id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, quantity INT NOT NULL, carbs_per_unit_grams INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8833115D8D41DA24 ON nutrition_items (segment_nutrition_plan_id)');
        $this->addSql('CREATE TABLE nutrition_plans (id VARCHAR(36) NOT NULL, runner_race_id VARCHAR(36) NOT NULL, name VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN nutrition_plans.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE segment_nutrition_plans (id VARCHAR(36) NOT NULL, nutrition_plan_id VARCHAR(36) NOT NULL, segment_id VARCHAR(36) NOT NULL, "order" INT NOT NULL, grams INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E9AD3803113D03C9 ON segment_nutrition_plans (nutrition_plan_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_segment_nutrition_plan_plan_segment ON segment_nutrition_plans (nutrition_plan_id, segment_id)');
        $this->addSql('ALTER TABLE nutrition_items ADD CONSTRAINT FK_8833115D8D41DA24 FOREIGN KEY (segment_nutrition_plan_id) REFERENCES segment_nutrition_plans (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE segment_nutrition_plans ADD CONSTRAINT FK_E9AD3803113D03C9 FOREIGN KEY (nutrition_plan_id) REFERENCES nutrition_plans (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE segment DROP CONSTRAINT fk_1881f565113d03c9');
        $this->addSql('ALTER TABLE segment DROP CONSTRAINT fk_1881f56513b24925');
        $this->addSql('ALTER TABLE segment DROP CONSTRAINT fk_1881f5656f6240f0');
        $this->addSql('ALTER TABLE checkpoint DROP CONSTRAINT fk_f00f7be113d03c9');
        $this->addSql('ALTER TABLE checkpoint DROP CONSTRAINT fk_f00f7bebaa58852');
        $this->addSql('ALTER TABLE nutrition_item DROP CONSTRAINT fk_3421cb8ddb296aad');
        $this->addSql('ALTER TABLE nutrition_plan DROP CONSTRAINT fk_f660b5ee6e59d40d');
        $this->addSql('DROP TABLE segment');
        $this->addSql('DROP TABLE imported_race');
        $this->addSql('DROP TABLE checkpoint');
        $this->addSql('DROP TABLE nutrition_item');
        $this->addSql('DROP TABLE nutrition_plan');
        $this->addSql('ALTER TABLE checkpoints ALTER cutoff_offset_in_minutes SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE segment (id VARCHAR(255) NOT NULL, start_checkpoint_id VARCHAR(255) NOT NULL, end_checkpoint_id VARCHAR(255) NOT NULL, nutrition_plan_id VARCHAR(255) NOT NULL, "position" INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_1881f565113d03c9 ON segment (nutrition_plan_id)');
        $this->addSql('CREATE INDEX idx_1881f56513b24925 ON segment (end_checkpoint_id)');
        $this->addSql('CREATE INDEX idx_1881f5656f6240f0 ON segment (start_checkpoint_id)');
        $this->addSql('CREATE TABLE imported_race (id VARCHAR(255) NOT NULL, runner_id VARCHAR(255) NOT NULL, external_race_id VARCHAR(255) NOT NULL, external_event_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, distance INT NOT NULL, ascent INT NOT NULL, descent INT NOT NULL, start_date_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, location VARCHAR(255) NOT NULL, event_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN imported_race.start_date_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE checkpoint (id VARCHAR(255) NOT NULL, imported_race_id VARCHAR(255) DEFAULT NULL, nutrition_plan_id VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, distance_from_start INT NOT NULL, ascent_from_start INT NOT NULL, descent_from_start INT NOT NULL, assistance_allowed BOOLEAN NOT NULL, cutoff_time TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, checkpoint_class VARCHAR(255) NOT NULL, external_id VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_f00f7be113d03c9 ON checkpoint (nutrition_plan_id)');
        $this->addSql('CREATE INDEX idx_f00f7bebaa58852 ON checkpoint (imported_race_id)');
        $this->addSql('COMMENT ON COLUMN checkpoint.cutoff_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE nutrition_item (id VARCHAR(255) NOT NULL, segment_id VARCHAR(255) NOT NULL, external_reference VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, carbs_value INT NOT NULL, quantity_value INT NOT NULL, calories_value INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_3421cb8ddb296aad ON nutrition_item (segment_id)');
        $this->addSql('CREATE TABLE nutrition_plan (id VARCHAR(255) NOT NULL, race_id VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_f660b5ee6e59d40d ON nutrition_plan (race_id)');
        $this->addSql('COMMENT ON COLUMN nutrition_plan.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE segment ADD CONSTRAINT fk_1881f565113d03c9 FOREIGN KEY (nutrition_plan_id) REFERENCES nutrition_plan (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE segment ADD CONSTRAINT fk_1881f56513b24925 FOREIGN KEY (end_checkpoint_id) REFERENCES checkpoint (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE segment ADD CONSTRAINT fk_1881f5656f6240f0 FOREIGN KEY (start_checkpoint_id) REFERENCES checkpoint (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE checkpoint ADD CONSTRAINT fk_f00f7be113d03c9 FOREIGN KEY (nutrition_plan_id) REFERENCES nutrition_plan (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE checkpoint ADD CONSTRAINT fk_f00f7bebaa58852 FOREIGN KEY (imported_race_id) REFERENCES imported_race (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE nutrition_item ADD CONSTRAINT fk_3421cb8ddb296aad FOREIGN KEY (segment_id) REFERENCES segment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE nutrition_plan ADD CONSTRAINT fk_f660b5ee6e59d40d FOREIGN KEY (race_id) REFERENCES imported_race (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE nutrition_items DROP CONSTRAINT FK_8833115D8D41DA24');
        $this->addSql('ALTER TABLE segment_nutrition_plans DROP CONSTRAINT FK_E9AD3803113D03C9');
        $this->addSql('DROP TABLE nutrition_items');
        $this->addSql('DROP TABLE nutrition_plans');
        $this->addSql('DROP TABLE segment_nutrition_plans');
        $this->addSql('ALTER TABLE checkpoints ALTER cutoff_offset_in_minutes DROP NOT NULL');
    }
}
