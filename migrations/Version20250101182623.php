<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250101182623 extends AbstractMigration
{
    public function getDescription(): string
    {
        return "Make grocery quantity nullable";
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            "CREATE TABLE grocery_tmp
            (
                id             INTEGER PRIMARY KEY,
                day_id         INTEGER NOT NULL REFERENCES day ON DELETE CASCADE,
                quantity_value REAL,
                quantity_unit  TEXT,
                name           TEXT    NOT NULL
            ) STRICT",
        );
        $this->addSql(
            "INSERT INTO grocery_tmp(id, day_id, quantity_value, quantity_unit, name)
            SELECT id, day_id, quantity_value, quantity_unit, name FROM grocery",
        );
        $this->addSql("DROP TABLE grocery");
        $this->addSql("ALTER TABLE grocery_tmp RENAME TO grocery");
    }

    public function down(Schema $schema): void
    {
        $this->addSql(
            "CREATE TABLE grocery_tmp
            (
                id             INTEGER PRIMARY KEY,
                day_id         INTEGER NOT NULL REFERENCES day ON DELETE CASCADE,
                quantity_value REAL    NOT NULL,
                quantity_unit  TEXT    NOT NULL,
                name           TEXT    NOT NULL
            ) STRICT",
        );
        $this->addSql(
            "INSERT INTO grocery_tmp(id, day_id, quantity_value, quantity_unit, name)
            SELECT id, day_id, quantity_value, quantity_unit, name 
            FROM grocery
            WHERE quantity_value IS NOT NULL AND quantity_unit IS NOT NULL",
        );
        $this->addSql("DROP TABLE grocery");
        $this->addSql("ALTER TABLE grocery_tmp RENAME TO grocery");
    }
}
