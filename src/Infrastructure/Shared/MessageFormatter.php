<?php

namespace App\Infrastructure\Shared;

use Symfony\Component\Translation\Formatter\IntlFormatter;
use Symfony\Component\Translation\Formatter\IntlFormatterInterface;
use Symfony\Component\Translation\Formatter\MessageFormatterInterface;

class MessageFormatter implements
    MessageFormatterInterface,
    IntlFormatterInterface
{
    private readonly IntlFormatterInterface $formatter;

    public function __construct()
    {
        $this->formatter = new IntlFormatter();
    }

    public function format(
        string $message,
        string $locale,
        array $parameters = [],
    ): string {
        return $this->formatter->formatIntl($message, $locale, $parameters);
    }

    public function formatIntl(
        string $message,
        string $locale,
        array $parameters = [],
    ): string {
        return $this->formatter->formatIntl($message, $locale, $parameters);
    }
}
