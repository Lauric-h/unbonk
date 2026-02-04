<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260204154957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change relations between race, nutrition plan and runner';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE imported_race ADD runner_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE nutrition_plan DROP FOREIGN KEY FK_F660B5EEBAA58852');
        $this->addSql('DROP INDEX UNIQ_F660B5EEBAA58852 ON nutrition_plan');
        $this->addSql('ALTER TABLE nutrition_plan ADD race_id VARCHAR(255) NOT NULL, ADD name VARCHAR(255) DEFAULT NULL, DROP imported_race_id, DROP runner_id');
        $this->addSql('ALTER TABLE nutrition_plan ADD CONSTRAINT FK_F660B5EE6E59D40D FOREIGN KEY (race_id) REFERENCES imported_race (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_F660B5EE6E59D40D ON nutrition_plan (race_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE nutrition_plan DROP FOREIGN KEY FK_F660B5EE6E59D40D');
        $this->addSql('DROP INDEX IDX_F660B5EE6E59D40D ON nutrition_plan');
        $this->addSql('ALTER TABLE nutrition_plan ADD runner_id VARCHAR(255) NOT NULL, DROP name, CHANGE race_id imported_race_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE nutrition_plan ADD CONSTRAINT FK_F660B5EEBAA58852 FOREIGN KEY (imported_race_id) REFERENCES imported_race (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F660B5EEBAA58852 ON nutrition_plan (imported_race_id)');
        $this->addSql('ALTER TABLE imported_race DROP runner_id');
    }
}
