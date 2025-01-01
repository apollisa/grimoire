<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250101160255 extends AbstractMigration
{
    public function getDescription(): string
    {
        return "Add folder menu inclusion";
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            "ALTER TABLE folder ADD is_included_in_menus INTEGER NOT NULL DEFAULT TRUE",
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql("ALTER TABLE folder DROP is_included_in_menus");
    }
}
