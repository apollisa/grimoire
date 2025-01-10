<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250109151450 extends AbstractMigration
{
    public function getDescription(): string
    {
        return "Create tables";
    }

    public function up(Schema $schema): void
    {
        $this->createRecipeTables();
        $this->createMenuTables();
        $this->createGroceryTable();
    }

    private function createRecipeTables(): void
    {
        $this->addSql(
            "CREATE TABLE folder
            (
                id                   INTEGER PRIMARY KEY,
                name                 TEXT    NOT NULL,
                is_included_in_menus INTEGER NOT NULL DEFAULT FALSE
            ) STRICT",
        );
        $this->addSql(
            "CREATE TABLE recipe
            (
                id                 INTEGER PRIMARY KEY,
                updated_at         TEXT    NOT NULL DEFAULT CURRENT_TIMESTAMP,
                folder_id          INTEGER NOT NULL REFERENCES folder,
                name               TEXT    NOT NULL,
                servings           INTEGER NOT NULL,
                seasonality_starts INTEGER          DEFAULT 1,
                seasonality_ends   INTEGER          DEFAULT 12,
                ingredients        TEXT    NOT NULL DEFAULT '[]',
                instructions       TEXT    NOT NULL DEFAULT '[]'
            ) STRICT",
        );
    }

    private function createMenuTables(): void
    {
        $this->addSql("CREATE TABLE menu (id INTEGER PRIMARY KEY) STRICT");
        $this->addSql(
            "CREATE TABLE day
            (
                id      INTEGER PRIMARY KEY,
                menu_id INTEGER NOT NULL REFERENCES menu ON DELETE CASCADE,
                date    TEXT    NOT NULL
            ) STRICT",
        );
        $this->addSql(
            "CREATE TABLE meal
            (
                id                 INTEGER PRIMARY KEY,
                day_id             INTEGER NOT NULL REFERENCES day ON DELETE CASCADE,
                recipe_id          INTEGER NOT NULL REFERENCES recipe,
                remaining_servings INTEGER NOT NULL,
                remains_of_id      INTEGER REFERENCES meal ON DELETE CASCADE
            ) STRICT",
        );
    }

    private function createGroceryTable(): void
    {
        $this->addSql(
            "CREATE TABLE grocery
            (
                id             INTEGER PRIMARY KEY,
                day_id         INTEGER NOT NULL REFERENCES day ON DELETE CASCADE,
                name           TEXT    NOT NULL,
                quantity_value REAL,
                quantity_unit  TEXT
            ) STRICT",
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DROP TABLE grocery");
        $this->addSql("DROP TABLE meal");
        $this->addSql("DROP TABLE recipe");
        $this->addSql("DROP TABLE folder");
        $this->addSql("DROP TABLE day");
        $this->addSql("DROP TABLE menu");
    }
}
