<?php

namespace App\Tests\Infrastructure\Shared;

use App\Infrastructure\Shared\ContentSecurityPolicyGenerator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;

class ContentSecurityPolicyGeneratorTest extends WebTestCase
{
    private AbstractBrowser $client;
    private ContentSecurityPolicyGenerator $generator;

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->generator = self::getContainer()->get(
            ContentSecurityPolicyGenerator::class,
        );
    }

    public function testNonceIsNotEmpty()
    {
        self::assertNotEmpty($this->generator->getNonce());
    }

    public function testNonceIsIncludedInScript()
    {
        $nonce = $this->generator->getNonce();

        $this->client->request("GET", "/");

        self::assertSelectorExists("script[nonce='$nonce']");
    }
}
