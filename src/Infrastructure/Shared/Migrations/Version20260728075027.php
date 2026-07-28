<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260728075027 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Set cutoff not null';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE checkpoints ALTER cutoff_offset_in_minutes SET NOT NULL');
    }
}
