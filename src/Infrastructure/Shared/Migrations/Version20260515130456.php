<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260515130456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Base migration';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE app_user (id VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_88BDF3E9F85E0677 ON app_user (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_88BDF3E9E7927C74 ON app_user (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_88BDF3E935C246D5 ON app_user (password)');
        $this->addSql('CREATE TABLE brand (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1C52F9585E237E06 ON brand (name)');
        $this->addSql('CREATE TABLE checkpoint (id VARCHAR(255) NOT NULL, runner_race_id VARCHAR(255) NOT NULL, external_checkpoint_id VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, distance_from_start INT NOT NULL, ascent_from_start INT NOT NULL, descent_from_start INT NOT NULL, assistance_allowed BOOLEAN NOT NULL, type VARCHAR(255) NOT NULL, cutoff_date_time TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_checkpoint_runner_race ON checkpoint (runner_race_id)');
        $this->addSql('CREATE INDEX idx_checkpoint_external_id ON checkpoint (external_checkpoint_id)');
        $this->addSql('COMMENT ON COLUMN checkpoint.cutoff_date_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE food (id VARCHAR(255) NOT NULL, brand_id VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, carbs INT NOT NULL, ingestion_type VARCHAR(255) NOT NULL, calories INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D43829F75E237E06 ON food (name)');
        $this->addSql('CREATE INDEX IDX_D43829F744F5D008 ON food (brand_id)');
        $this->addSql('CREATE TABLE nutrition_item (id VARCHAR(255) NOT NULL, segment_nutrition_plan_id VARCHAR(255) NOT NULL, food_item_id VARCHAR(255) NOT NULL, quantity_value INT NOT NULL, carbs_value INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_nutrition_item_segment_plan ON nutrition_item (segment_nutrition_plan_id)');
        $this->addSql('CREATE INDEX idx_nutrition_item_food_item ON nutrition_item (food_item_id)');
        $this->addSql('CREATE TABLE nutrition_plan (id VARCHAR(255) NOT NULL, runner_race_id VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_nutrition_plan_runner_race ON nutrition_plan (runner_race_id)');
        $this->addSql('COMMENT ON COLUMN nutrition_plan.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE runner_race (id VARCHAR(255) NOT NULL, runner_id VARCHAR(255) NOT NULL, source_race_id VARCHAR(255) NOT NULL, event_id VARCHAR(255) NOT NULL, event_name VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, distance INT NOT NULL, ascent INT NOT NULL, descent INT NOT NULL, start_date_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, location VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN runner_race.start_date_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE segment (id VARCHAR(255) NOT NULL, runner_race_id VARCHAR(255) NOT NULL, from_checkpoint_id VARCHAR(255) NOT NULL, to_checkpoint_id VARCHAR(255) NOT NULL, "order" INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1881F565BB2860C8 ON segment (from_checkpoint_id)');
        $this->addSql('CREATE INDEX IDX_1881F5657DCD6A03 ON segment (to_checkpoint_id)');
        $this->addSql('CREATE INDEX idx_segment_runner_race ON segment (runner_race_id)');
        $this->addSql('CREATE INDEX idx_segment_checkpoints ON segment (from_checkpoint_id, to_checkpoint_id)');
        $this->addSql('CREATE TABLE segment_nutrition_plan (id VARCHAR(255) NOT NULL, nutrition_plan_id VARCHAR(255) NOT NULL, segment_id VARCHAR(255) NOT NULL, target_carbs_value INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_segment_nutrition_plan_nutrition_plan ON segment_nutrition_plan (nutrition_plan_id)');
        $this->addSql('CREATE INDEX idx_segment_nutrition_plan_segment ON segment_nutrition_plan (segment_id)');
        $this->addSql('ALTER TABLE checkpoint ADD CONSTRAINT FK_F00F7BE28080DEE FOREIGN KEY (runner_race_id) REFERENCES runner_race (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE food ADD CONSTRAINT FK_D43829F744F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE nutrition_item ADD CONSTRAINT FK_3421CB8D8D41DA24 FOREIGN KEY (segment_nutrition_plan_id) REFERENCES segment_nutrition_plan (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE nutrition_plan ADD CONSTRAINT FK_F660B5EE28080DEE FOREIGN KEY (runner_race_id) REFERENCES runner_race (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE segment ADD CONSTRAINT FK_1881F56528080DEE FOREIGN KEY (runner_race_id) REFERENCES runner_race (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE segment ADD CONSTRAINT FK_1881F565BB2860C8 FOREIGN KEY (from_checkpoint_id) REFERENCES checkpoint (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE segment ADD CONSTRAINT FK_1881F5657DCD6A03 FOREIGN KEY (to_checkpoint_id) REFERENCES checkpoint (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE segment_nutrition_plan ADD CONSTRAINT FK_C1F74653113D03C9 FOREIGN KEY (nutrition_plan_id) REFERENCES nutrition_plan (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE segment_nutrition_plan ADD CONSTRAINT FK_C1F74653DB296AAD FOREIGN KEY (segment_id) REFERENCES segment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE checkpoint DROP CONSTRAINT FK_F00F7BE28080DEE');
        $this->addSql('ALTER TABLE food DROP CONSTRAINT FK_D43829F744F5D008');
        $this->addSql('ALTER TABLE nutrition_item DROP CONSTRAINT FK_3421CB8D8D41DA24');
        $this->addSql('ALTER TABLE nutrition_plan DROP CONSTRAINT FK_F660B5EE28080DEE');
        $this->addSql('ALTER TABLE segment DROP CONSTRAINT FK_1881F56528080DEE');
        $this->addSql('ALTER TABLE segment DROP CONSTRAINT FK_1881F565BB2860C8');
        $this->addSql('ALTER TABLE segment DROP CONSTRAINT FK_1881F5657DCD6A03');
        $this->addSql('ALTER TABLE segment_nutrition_plan DROP CONSTRAINT FK_C1F74653113D03C9');
        $this->addSql('ALTER TABLE segment_nutrition_plan DROP CONSTRAINT FK_C1F74653DB296AAD');
        $this->addSql('DROP TABLE app_user');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP TABLE checkpoint');
        $this->addSql('DROP TABLE food');
        $this->addSql('DROP TABLE nutrition_item');
        $this->addSql('DROP TABLE nutrition_plan');
        $this->addSql('DROP TABLE runner_race');
        $this->addSql('DROP TABLE segment');
        $this->addSql('DROP TABLE segment_nutrition_plan');
    }
}
