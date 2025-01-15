<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250114141645 extends AbstractMigration
{
    public function getDescription(): string
    {
        return "Add recipe slug";
    }

    public function up(Schema $schema): void
    {
        $this->addSql("ALTER TABLE recipe ADD slug TEXT");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("ALTER TABLE recipe DROP slug");
    }
}
