<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250921173517 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nutrition_plan DROP race_id');
        $this->addSql('ALTER TABLE race ADD nutrition_plan_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE race ADD CONSTRAINT FK_DA6FBBAF113D03C9 FOREIGN KEY (nutrition_plan_id) REFERENCES nutrition_plan (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DA6FBBAF113D03C9 ON race (nutrition_plan_id)');
        $this->addSql('ALTER TABLE segment ADD race_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE segment ADD CONSTRAINT FK_1881F5656E59D40D FOREIGN KEY (race_id) REFERENCES race (id)');
        $this->addSql('ALTER TABLE segment ADD CONSTRAINT FK_1881F565623DF99B FOREIGN KEY (start_id) REFERENCES checkpoint (id)');
        $this->addSql('ALTER TABLE segment ADD CONSTRAINT FK_1881F5652B4667EB FOREIGN KEY (finish_id) REFERENCES checkpoint (id)');
        $this->addSql('CREATE INDEX IDX_1881F5656E59D40D ON segment (race_id)');
        $this->addSql('CREATE INDEX IDX_1881F565623DF99B ON segment (start_id)');
        $this->addSql('CREATE INDEX IDX_1881F5652B4667EB ON segment (finish_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nutrition_plan ADD race_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE segment DROP FOREIGN KEY FK_1881F5656E59D40D');
        $this->addSql('ALTER TABLE segment DROP FOREIGN KEY FK_1881F565623DF99B');
        $this->addSql('ALTER TABLE segment DROP FOREIGN KEY FK_1881F5652B4667EB');
        $this->addSql('DROP INDEX IDX_1881F5656E59D40D ON segment');
        $this->addSql('DROP INDEX IDX_1881F565623DF99B ON segment');
        $this->addSql('DROP INDEX IDX_1881F5652B4667EB ON segment');
        $this->addSql('ALTER TABLE segment DROP race_id');
        $this->addSql('ALTER TABLE race DROP FOREIGN KEY FK_DA6FBBAF113D03C9');
        $this->addSql('DROP INDEX UNIQ_DA6FBBAF113D03C9 ON race');
        $this->addSql('ALTER TABLE race DROP nutrition_plan_id');
    }
}
