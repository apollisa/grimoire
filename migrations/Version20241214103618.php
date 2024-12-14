<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241214103618 extends AbstractMigration
{
    public function getDescription(): string
    {
        return "Add menu table";
    }

    public function up(Schema $schema): void
    {
        $this->addSql("CREATE TABLE menu(id INTEGER PRIMARY KEY) STRICT");
        $this->addSql(
            "CREATE TABLE day
            (
                id      INTEGER PRIMARY KEY,
                menu_id INTEGER NOT NULL REFERENCES menu ON DELETE CASCADE,
                date    TEXT NOT NULL
            ) STRICT",
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DROP TABLE day");
        $this->addSql("DROP TABLE menu");
    }
}
