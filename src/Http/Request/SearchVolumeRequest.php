<?php

declare(strict_types=1);

namespace Emeq\DataForSeoApi\Http\Request;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class SearchVolumeRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  list<string>  $keywords
     * @param  array<string, mixed>  $options
     */
    public function __construct(
        private readonly array $keywords,
        private readonly array $options = [],
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v3/keywords_data/google_ads/search_volume/live';
    }

    /** @return list<array<string, mixed>> */
    protected function defaultBody(): array
    {
        return [[
            'keywords' => $this->keywords,
            'location_code' => 2528,
            'language_code' => 'nl',
            ...$this->options,
        ]];
    }
}
