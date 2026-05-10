<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260510180948 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE brand (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1C52F9585E237E06 ON brand (name)');
        $this->addSql('CREATE TABLE checkpoint (id VARCHAR(255) NOT NULL, imported_race_id VARCHAR(255) DEFAULT NULL, nutrition_plan_id VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, distance_from_start INT NOT NULL, ascent_from_start INT NOT NULL, descent_from_start INT NOT NULL, assistance_allowed BOOLEAN NOT NULL, cutoff_time TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, checkpoint_class VARCHAR(255) NOT NULL, external_id VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F00F7BEBAA58852 ON checkpoint (imported_race_id)');
        $this->addSql('CREATE INDEX IDX_F00F7BE113D03C9 ON checkpoint (nutrition_plan_id)');
        $this->addSql('COMMENT ON COLUMN checkpoint.cutoff_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE food (id VARCHAR(255) NOT NULL, brand_id VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, carbs INT NOT NULL, ingestion_type VARCHAR(255) NOT NULL, calories INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D43829F75E237E06 ON food (name)');
        $this->addSql('CREATE INDEX IDX_D43829F744F5D008 ON food (brand_id)');
        $this->addSql('CREATE TABLE imported_race (id VARCHAR(255) NOT NULL, runner_id VARCHAR(255) NOT NULL, external_race_id VARCHAR(255) NOT NULL, external_event_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, distance INT NOT NULL, ascent INT NOT NULL, descent INT NOT NULL, start_date_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, location VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN imported_race.start_date_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE nutrition_item (id VARCHAR(255) NOT NULL, segment_id VARCHAR(255) NOT NULL, external_reference VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, carbs_value INT NOT NULL, quantity_value INT NOT NULL, calories_value INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3421CB8DDB296AAD ON nutrition_item (segment_id)');
        $this->addSql('CREATE TABLE nutrition_plan (id VARCHAR(255) NOT NULL, race_id VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F660B5EE6E59D40D ON nutrition_plan (race_id)');
        $this->addSql('COMMENT ON COLUMN nutrition_plan.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE segment (id VARCHAR(255) NOT NULL, start_checkpoint_id VARCHAR(255) NOT NULL, end_checkpoint_id VARCHAR(255) NOT NULL, nutrition_plan_id VARCHAR(255) NOT NULL, position INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1881F5656F6240F0 ON segment (start_checkpoint_id)');
        $this->addSql('CREATE INDEX IDX_1881F56513B24925 ON segment (end_checkpoint_id)');
        $this->addSql('CREATE INDEX IDX_1881F565113D03C9 ON segment (nutrition_plan_id)');
        $this->addSql('CREATE TABLE "user" (id VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64935C246D5 ON "user" (password)');
        $this->addSql('ALTER TABLE checkpoint ADD CONSTRAINT FK_F00F7BEBAA58852 FOREIGN KEY (imported_race_id) REFERENCES imported_race (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE checkpoint ADD CONSTRAINT FK_F00F7BE113D03C9 FOREIGN KEY (nutrition_plan_id) REFERENCES nutrition_plan (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE food ADD CONSTRAINT FK_D43829F744F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE nutrition_item ADD CONSTRAINT FK_3421CB8DDB296AAD FOREIGN KEY (segment_id) REFERENCES segment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE nutrition_plan ADD CONSTRAINT FK_F660B5EE6E59D40D FOREIGN KEY (race_id) REFERENCES imported_race (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE segment ADD CONSTRAINT FK_1881F5656F6240F0 FOREIGN KEY (start_checkpoint_id) REFERENCES checkpoint (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE segment ADD CONSTRAINT FK_1881F56513B24925 FOREIGN KEY (end_checkpoint_id) REFERENCES checkpoint (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE segment ADD CONSTRAINT FK_1881F565113D03C9 FOREIGN KEY (nutrition_plan_id) REFERENCES nutrition_plan (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE checkpoint DROP CONSTRAINT FK_F00F7BEBAA58852');
        $this->addSql('ALTER TABLE checkpoint DROP CONSTRAINT FK_F00F7BE113D03C9');
        $this->addSql('ALTER TABLE food DROP CONSTRAINT FK_D43829F744F5D008');
        $this->addSql('ALTER TABLE nutrition_item DROP CONSTRAINT FK_3421CB8DDB296AAD');
        $this->addSql('ALTER TABLE nutrition_plan DROP CONSTRAINT FK_F660B5EE6E59D40D');
        $this->addSql('ALTER TABLE segment DROP CONSTRAINT FK_1881F5656F6240F0');
        $this->addSql('ALTER TABLE segment DROP CONSTRAINT FK_1881F56513B24925');
        $this->addSql('ALTER TABLE segment DROP CONSTRAINT FK_1881F565113D03C9');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP TABLE checkpoint');
        $this->addSql('DROP TABLE food');
        $this->addSql('DROP TABLE imported_race');
        $this->addSql('DROP TABLE nutrition_item');
        $this->addSql('DROP TABLE nutrition_plan');
        $this->addSql('DROP TABLE segment');
        $this->addSql('DROP TABLE "user"');
    }
}
