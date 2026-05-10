<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Refactor checkpoints: separate ImportedCheckpoint and CustomCheckpoint
 * using Single Table Inheritance
 */
final class Version20260508000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Refactor checkpoints to use Single Table Inheritance with ImportedCheckpoint and CustomCheckpoint';
    }

    public function up(Schema $schema): void
    {
        // Add discriminator column for Single Table Inheritance
        $this->addSql('ALTER TABLE checkpoint ADD checkpoint_class VARCHAR(255) NOT NULL DEFAULT \'imported\'');
        
        // Add nutrition_plan_id for CustomCheckpoint (nullable because ImportedCheckpoint won't have it)
        $this->addSql('ALTER TABLE checkpoint ADD nutrition_plan_id VARCHAR(255) DEFAULT NULL');
        
        // Make columns nullable for child classes that don't need them
        $this->addSql('ALTER TABLE checkpoint MODIFY external_id VARCHAR(255) NULL');
        $this->addSql('ALTER TABLE checkpoint MODIFY type VARCHAR(255) NULL');
        $this->addSql('ALTER TABLE checkpoint MODIFY imported_race_id VARCHAR(255) NULL');
        
        // Add foreign key for nutrition_plan_id
        $this->addSql('ALTER TABLE checkpoint ADD CONSTRAINT FK_C4663E6D9E8E1E23 FOREIGN KEY (nutrition_plan_id) REFERENCES nutrition_plan (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_C4663E6D9E8E1E23 ON checkpoint (nutrition_plan_id)');
        
        // Set discriminator for existing checkpoints (all are imported)
        $this->addSql('UPDATE checkpoint SET checkpoint_class = \'imported\' WHERE checkpoint_class = \'imported\'');
    }

    public function down(Schema $schema): void
    {
        // Remove foreign key and index
        $this->addSql('ALTER TABLE checkpoint DROP FOREIGN KEY FK_C4663E6D9E8E1E23');
        $this->addSql('DROP INDEX IDX_C4663E6D9E8E1E23 ON checkpoint');
        
        // Remove new columns
        $this->addSql('ALTER TABLE checkpoint DROP nutrition_plan_id');
        $this->addSql('ALTER TABLE checkpoint DROP checkpoint_class');
        
        // Restore NOT NULL constraints
        $this->addSql('ALTER TABLE checkpoint MODIFY external_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE checkpoint MODIFY type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE checkpoint MODIFY imported_race_id VARCHAR(255) NOT NULL');
    }
}
