<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241228210325 extends AbstractMigration
{
    public function getDescription(): string
    {
        return "Add grocery table";
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            "CREATE TABLE grocery
            (
                id             INTEGER PRIMARY KEY,
                day_id         INTEGER NOT NULL REFERENCES day ON DELETE CASCADE,
                quantity_value REAL    NOT NULL,
                quantity_unit  TEXT    NOT NULL,
                name           TEXT    NOT NULL
            ) STRICT",
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DROP TABLE grocery");
    }
}
