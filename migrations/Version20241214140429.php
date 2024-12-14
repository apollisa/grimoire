<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241214140429 extends AbstractMigration
{
    public function getDescription(): string
    {
        return "Add recipe table";
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            "CREATE TABLE recipe(id INTEGER PRIMARY KEY, name TEXT NOT NULL, servings INTEGER NOT NULL) STRICT",
        );
        $this->addSql(
            "CREATE TABLE meal
                (
                    id                 INTEGER PRIMARY KEY,
                    day_id             INTEGER NOT NULL REFERENCES day ON DELETE CASCADE,
                    recipe_id          INTEGER NOT NULL REFERENCES recipe ON DELETE CASCADE,
                    remaining_servings INTEGER NOT NULL,
                    remains_of_id      INTEGER REFERENCES meal ON DELETE CASCADE
                ) STRICT",
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DROP TABLE meal");
        $this->addSql("DROP TABLE recipe");
    }
}
