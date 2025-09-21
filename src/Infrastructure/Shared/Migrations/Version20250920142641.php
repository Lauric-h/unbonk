<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250920142641 extends AbstractMigration
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
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nutrition_plan ADD race_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE race DROP FOREIGN KEY FK_DA6FBBAF113D03C9');
        $this->addSql('DROP INDEX UNIQ_DA6FBBAF113D03C9 ON race');
        $this->addSql('ALTER TABLE race DROP nutrition_plan_id');
    }
}
