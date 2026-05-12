<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260512094121 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add event_name column to imported_race table';
    }

    public function up(Schema $schema): void
    {
        // Add column as nullable first
        $this->addSql('ALTER TABLE imported_race ADD event_name VARCHAR(255) DEFAULT NULL');
        
        // Fill existing rows with race name (temporary solution)
        $this->addSql('UPDATE imported_race SET event_name = name WHERE event_name IS NULL');
        
        // Make column NOT NULL
        $this->addSql('ALTER TABLE imported_race ALTER COLUMN event_name SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE imported_race DROP event_name');
    }
}
