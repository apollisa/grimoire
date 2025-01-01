<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250101112433 extends AbstractMigration
{
    public function getDescription(): string
    {
        return "Add folders";
    }

    public function isTransactional(): bool
    {
        return false;
    }

    public function up(Schema $schema): void
    {
        $this->addSql("BEGIN TRANSACTION IMMEDIATE");
        $this->addSql(
            "CREATE TABLE folder(id INTEGER PRIMARY KEY, name TEXT NOT NULL) STRICT",
        );
        $this->addSql("INSERT INTO folder(name) VALUES('Plats')");
        $this->addSql("COMMIT TRANSACTION");
        $this->makeFolderIdNotNull();
    }

    private function makeFolderIdNotNull(): void
    {
        $this->addSql("PRAGMA foreign_keys = 0");
        $this->addSql("BEGIN TRANSACTION IMMEDIATE");
        $this->addSql(
            "CREATE TABLE recipe_tmp
            (
                id                 INTEGER PRIMARY KEY,
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
            "INSERT INTO recipe_tmp(id, folder_id, name, servings, seasonality_starts, seasonality_ends, ingredients, instructions)
            SELECT recipe.id, folder.id, recipe.name, servings, seasonality_starts, seasonality_ends, ingredients, instructions FROM recipe, folder",
        );
        $this->addSql("DROP TABLE recipe");
        $this->addSql("ALTER TABLE recipe_tmp RENAME TO recipe");
        $this->addSql("COMMIT TRANSACTION");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("ALTER TABLE recipe DROP folder_id");
        $this->addSql("DROP TABLE folder");
    }
}
