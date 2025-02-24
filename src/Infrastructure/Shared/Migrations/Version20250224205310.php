<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250224205310 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Food entity and map to Brand';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE food (id VARCHAR(255) NOT NULL, brand_id VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, carbs INT NOT NULL, ingestion_type VARCHAR(255) NOT NULL, calories INT DEFAULT NULL, UNIQUE INDEX UNIQ_D43829F75E237E06 (name), INDEX IDX_D43829F744F5D008 (brand_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE food ADD CONSTRAINT FK_D43829F744F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE food DROP FOREIGN KEY FK_D43829F744F5D008');
        $this->addSql('DROP TABLE food');
    }
}
