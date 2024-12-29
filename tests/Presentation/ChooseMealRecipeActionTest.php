<?php

namespace App\Tests\Presentation;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ChooseMealRecipeActionTest extends WebTestCase
{
    public function testDoNotOfferRemainsBeforeCreation(): void
    {
        $client = self::createClient();

        $client->request("GET", "/1/recettes", ["jour" => 1]);

        self::assertSelectorNotExists('optgroup[label="Restes"]');
    }
}
