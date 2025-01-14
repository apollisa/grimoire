<?php

namespace App\Tests\Infrastructure\Shared;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SQLiteEncodingTest extends KernelTestCase
{
    public function testUsesUnicode(): void
    {
        $connection = self::getContainer()->get(Connection::class);

        self::assertEquals(
            '["Préparer une purée"]',
            $connection
                ->executeQuery("SELECT instructions FROM recipe WHERE id = 1")
                ->fetchOne(),
        );
    }
}
