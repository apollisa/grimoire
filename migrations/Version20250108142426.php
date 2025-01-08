<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250108142426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return "Make seasonality nullable";
    }

    public function isTransactional(): bool
    {
        return false;
    }

    public function up(Schema $schema): void
    {
        $this->addSql("PRAGMA foreign_keys = 0");
        $this->addSql("BEGIN TRANSACTION IMMEDIATE");
        $this->addSql(
            "CREATE TABLE recipe_tmp
                (
                    id                 INTEGER PRIMARY KEY,
                    updated_at         TEXT                 NOT NULL,
                    folder_id          INTEGER              NOT NULL REFERENCES folder,
                    name               TEXT                 NOT NULL,
                    servings           INTEGER              NOT NULL,
                    seasonality_starts INTEGER DEFAULT 1,
                    seasonality_ends   INTEGER DEFAULT 12,
                    ingredients        TEXT    DEFAULT '[]' NOT NULL,
                    instructions       TEXT    DEFAULT '[]' NOT NULL
                ) STRICT",
        );
        $this->addSql(
            "INSERT INTO recipe_tmp(id, updated_at, folder_id, name, servings, seasonality_starts, seasonality_ends, ingredients, instructions)
            SELECT id, updated_at, folder_id, name, servings, seasonality_starts, seasonality_ends, ingredients, instructions
            FROM recipe",
        );
        $this->addSql("DROP TABLE recipe");
        $this->addSql("ALTER TABLE recipe_tmp RENAME TO recipe");
        $this->addSql("COMMIT TRANSACTION");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("PRAGMA foreign_keys = 0");
        $this->addSql("BEGIN TRANSACTION IMMEDIATE");
        $this->addSql(
            "CREATE TABLE recipe_tmp
                (
                    id                 INTEGER PRIMARY KEY,
                    updated_at         TEXT                 NOT NULL,
                    folder_id          INTEGER              NOT NULL REFERENCES folder,
                    name               TEXT                 NOT NULL,
                    servings           INTEGER              NOT NULL,
                    seasonality_starts INTEGER DEFAULT 1    NOT NULL,
                    seasonality_ends   INTEGER DEFAULT 12   NOT NULL,
                    ingredients        TEXT    DEFAULT '[]' NOT NULL,
                    instructions       TEXT    DEFAULT '[]' NOT NULL
                ) STRICT",
        );
        $this->addSql(
            "INSERT INTO recipe_tmp(id, updated_at, folder_id, name, servings, seasonality_starts, seasonality_ends, ingredients, instructions)
            SELECT id, updated_at, folder_id, name, servings, COALESCE(seasonality_starts, 1), COALESCE(seasonality_ends, 12), ingredients, instructions
            FROM recipe",
        );
        $this->addSql("DROP TABLE recipe");
        $this->addSql("ALTER TABLE recipe_tmp RENAME TO recipe");
        $this->addSql("COMMIT TRANSACTION");
    }
}
