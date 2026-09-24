<?php

declare(strict_types=1);

namespace Emeq\DataForSeoApi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array<string, mixed> domainOverview(string $domain, string $locationName = 'Netherlands')
 * @method static array<string, mixed> backlinksSummary(string $target, array<string, mixed> $extra = [])
 * @method static list<array<string, mixed>> searchVolume(list<string> $keywords, array<string, mixed> $options = [])
 * @method static array<string, mixed> serpOrganic(string $keyword, array<string, mixed> $options = [])
 * @method static array<string, mixed> relatedKeywords(string $keyword, array<string, mixed> $options = [])
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
