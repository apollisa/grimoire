<?php

namespace App\Tests\Presentation;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Clock\Test\ClockSensitiveTrait;

class DisplayMenuActionTest extends WebTestCase
{
    use ClockSensitiveTrait;

    private KernelBrowser $client;

    protected function setUp(): void
    {
        self::mockTime("2024-08-27");
        $this->client = self::createClient();
    }

    public function testDisplaysToday(): void
    {
        $this->client->request("GET", "/");

        self::assertSelectorTextContains("h2", "Mardi 27 août");
    }
}
