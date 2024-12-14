<?php

namespace App\Infrastructure\Shared;

use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Driver\Middleware;
use Doctrine\DBAL\Driver\Middleware\AbstractDriverMiddleware;

class SensibleSQLiteMiddleware implements Middleware
{
    public function wrap(Driver $driver): Driver
    {
        return new class ($driver) extends AbstractDriverMiddleware {
            public function connect(array $params): Connection
            {
                $wrapped = parent::connect($params);
                $wrapped->exec("PRAGMA busy_timeout = 5000");
                $wrapped->exec("PRAGMA foreign_keys = ON");
                $wrapped->exec("PRAGMA journal_mode = WAL");
                $wrapped->exec("PRAGMA synchronous = NORMAL");
                $wrapped->exec("PRAGMA mmap_size = 134217728");
                $wrapped->exec("PRAGMA journal_size_limit = 67108864");
                $wrapped->exec("PRAGMA cache_size = 2000");
                return $wrapped;
            }
        };
    }
}
