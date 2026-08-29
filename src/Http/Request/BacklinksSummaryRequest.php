<?php

declare(strict_types=1);

namespace Emeq\DataForSeoApi\Http\Request;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class BacklinksSummaryRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        private readonly string $target,
        private readonly array $extra = [],
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v3/backlinks/summary/live';
    }

    protected function defaultBody(): array
    {
        $body = [
            'target' => $this->target,
            'include_subdomains' => true,
            'backlinks_status_type' => 'live',
        ];

        return [array_merge($body, $this->extra)];
    }
}
