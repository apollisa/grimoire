<?php

namespace App\Tests\Presentation;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ListGroceriesActionTest extends WebTestCase
{
    protected function setUp(): void
    {
        self::createClient()->request("GET", "/courses");
    }

    public function testListGroceries(): void
    {
        self::assertSelectorTextContains("li", "30 g beurre");
    }

    public function testListOrderedGroceries(): void
    {
        self::assertSelectorTextContains("li ~ li", "1 kg patates");
    }

    public function testDisplayExportButton(): void
    {
        self::assertSelectorExists("[aria-label='Exporter']");
    }
}
