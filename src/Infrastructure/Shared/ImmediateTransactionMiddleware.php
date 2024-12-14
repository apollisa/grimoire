<?php

namespace App\Infrastructure\Shared;

use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Driver\Middleware;
use Doctrine\DBAL\Driver\Middleware\AbstractConnectionMiddleware;
use Doctrine\DBAL\Driver\Middleware\AbstractDriverMiddleware;
use Symfony\Component\DependencyInjection\Attribute\WhenNot;

#[WhenNot("test")]
class ImmediateTransactionMiddleware implements Middleware
{
    public function wrap(Driver $driver): Driver
    {
        return new class ($driver) extends AbstractDriverMiddleware {
            public function connect(array $params): Connection
            {
                $wrapped = parent::connect($params);
                return new class ($wrapped) extends AbstractConnectionMiddleware
                {
                    public function beginTransaction(): void
                    {
                        $this->exec("BEGIN IMMEDIATE");
                    }
                };
            }
        };
    }
}
