<?php

namespace App\Infrastructure\Shared;

use Random\RandomException;
use RuntimeException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use function join;

class ContentSecurityPolicyGenerator
{
    private const DIRECTIVES = [
        "default-src 'self'",
        "base-uri 'none'",
        "form-action 'self'",
        "frame-ancestors 'none'",
        "object-src 'none'",
        "upgrade-insecure-requests",
    ];

    private ?string $nonce = null;

    #[AsEventListener]
    public function addHeader(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        if (!$response->isRedirection()) {
            $type = $response->headers->get("Content-Type");
            if ($type === null || str_starts_with("text/html", $type)) {
                $directives = self::DIRECTIVES;
                if ($this->nonce !== null) {
                    $directives[] = "script-src 'strict-dynamic' 'nonce-$this->nonce'";
                }
                $response->headers->set(
                    "Content-Security-Policy",
                    join(";", $directives),
                );
            }
        }
        $this->nonce = null;
    }

    public function getNonce(): string
    {
        try {
            return $this->nonce ??= base64_encode(random_bytes(16));
        } catch (RandomException $exception) {
            throw new RuntimeException(
                "Couldn’t generate nonce",
                previous: $exception,
            );
        }
    }
}
