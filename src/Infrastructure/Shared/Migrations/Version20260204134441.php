<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260204134441 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE brand (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1C52F9585E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE checkpoint (id VARCHAR(255) NOT NULL, imported_race_id VARCHAR(255) NOT NULL, external_id VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, distance_from_start INT NOT NULL, ascent_from_start INT NOT NULL, descent_from_start INT NOT NULL, assistance_allowed TINYINT(1) NOT NULL, cutoff_time DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_F00F7BEBAA58852 (imported_race_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE food (id VARCHAR(255) NOT NULL, brand_id VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, carbs INT NOT NULL, ingestion_type VARCHAR(255) NOT NULL, calories INT DEFAULT NULL, UNIQUE INDEX UNIQ_D43829F75E237E06 (name), INDEX IDX_D43829F744F5D008 (brand_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE imported_race (id VARCHAR(255) NOT NULL, external_race_id VARCHAR(255) NOT NULL, external_event_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, distance INT NOT NULL, ascent INT NOT NULL, descent INT NOT NULL, start_date_time DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', location VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nutrition_item (id VARCHAR(255) NOT NULL, segment_id VARCHAR(255) NOT NULL, external_reference VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, carbs_value INT NOT NULL, quantity_value INT NOT NULL, calories_value INT NOT NULL, INDEX IDX_3421CB8DDB296AAD (segment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nutrition_plan (id VARCHAR(255) NOT NULL, imported_race_id VARCHAR(255) NOT NULL, runner_id VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_F660B5EEBAA58852 (imported_race_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE segment (id VARCHAR(255) NOT NULL, start_checkpoint_id VARCHAR(255) NOT NULL, end_checkpoint_id VARCHAR(255) NOT NULL, nutrition_plan_id VARCHAR(255) NOT NULL, position INT NOT NULL, INDEX IDX_1881F5656F6240F0 (start_checkpoint_id), INDEX IDX_1881F56513B24925 (end_checkpoint_id), INDEX IDX_1881F565113D03C9 (nutrition_plan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D64935C246D5 (password), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE checkpoint ADD CONSTRAINT FK_F00F7BEBAA58852 FOREIGN KEY (imported_race_id) REFERENCES imported_race (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE food ADD CONSTRAINT FK_D43829F744F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)');
        $this->addSql('ALTER TABLE nutrition_item ADD CONSTRAINT FK_3421CB8DDB296AAD FOREIGN KEY (segment_id) REFERENCES segment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE nutrition_plan ADD CONSTRAINT FK_F660B5EEBAA58852 FOREIGN KEY (imported_race_id) REFERENCES imported_race (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE segment ADD CONSTRAINT FK_1881F5656F6240F0 FOREIGN KEY (start_checkpoint_id) REFERENCES checkpoint (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE segment ADD CONSTRAINT FK_1881F56513B24925 FOREIGN KEY (end_checkpoint_id) REFERENCES checkpoint (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE segment ADD CONSTRAINT FK_1881F565113D03C9 FOREIGN KEY (nutrition_plan_id) REFERENCES nutrition_plan (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE checkpoint DROP FOREIGN KEY FK_F00F7BEBAA58852');
        $this->addSql('ALTER TABLE food DROP FOREIGN KEY FK_D43829F744F5D008');
        $this->addSql('ALTER TABLE nutrition_item DROP FOREIGN KEY FK_3421CB8DDB296AAD');
        $this->addSql('ALTER TABLE nutrition_plan DROP FOREIGN KEY FK_F660B5EEBAA58852');
        $this->addSql('ALTER TABLE segment DROP FOREIGN KEY FK_1881F5656F6240F0');
        $this->addSql('ALTER TABLE segment DROP FOREIGN KEY FK_1881F56513B24925');
        $this->addSql('ALTER TABLE segment DROP FOREIGN KEY FK_1881F565113D03C9');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP TABLE checkpoint');
        $this->addSql('DROP TABLE food');
        $this->addSql('DROP TABLE imported_race');
        $this->addSql('DROP TABLE nutrition_item');
        $this->addSql('DROP TABLE nutrition_plan');
        $this->addSql('DROP TABLE segment');
        $this->addSql('DROP TABLE user');
    }
}
