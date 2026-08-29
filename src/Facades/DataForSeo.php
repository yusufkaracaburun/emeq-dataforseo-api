<?php

declare(strict_types=1);

namespace Emeq\DataForSeoApi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array<string, mixed> domainOverview(string $domain)
 *
 * @see DataForSeo
 */
final class DataForSeo extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Emeq\DataForSeoApi\DataForSeo::class;
    }
}
