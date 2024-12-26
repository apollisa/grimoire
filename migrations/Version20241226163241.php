<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241226163241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return "Add seasonality";
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            "ALTER TABLE recipe ADD seasonality_starts INTEGER NOT NULL DEFAULT 1",
        );
        $this->addSql(
            "ALTER TABLE recipe ADD seasonality_ends INTEGER NOT NULL DEFAULT 12",
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql("ALTER TABLE recipe DROP seasonality_ends");
        $this->addSql("ALTER TABLE recipe DROP seasonality_starts");
    }
}
