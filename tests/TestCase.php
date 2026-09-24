<?php

declare(strict_types=1);

namespace Emeq\DataForSeoApi\Tests;

use Emeq\DataForSeoApi\DataForSeoServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            DataForSeoServiceProvider::class,
        ];
    }
}
