<?php

declare(strict_types=1);

namespace Emeq\DataForSeoApi\Http\Request;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class RelatedKeywordsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $options
     */
    public function __construct(
        private readonly string $keyword,
        private readonly array $options = [],
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v3/dataforseo_labs/google/related_keywords/live';
    }

    /** @return list<array<string, mixed>> */
    protected function defaultBody(): array
    {
        return [[
            'keyword' => $this->keyword,
            'location_code' => 2528,
            'language_code' => 'nl',
            ...$this->options,
        ]];
    }
}
