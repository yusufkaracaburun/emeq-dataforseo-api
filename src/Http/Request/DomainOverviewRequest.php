<?php

declare(strict_types=1);

namespace Emeq\DataForSeoApi\Http\Request;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class DomainOverviewRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        private readonly string $domain,
        private readonly string $locationName = 'Netherlands',
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v3/dataforseo_labs/google/domain_rank_overview/live';
    }

    protected function defaultBody(): array
    {
        return [
            [
                'target' => $this->domain,
                'location_name' => $this->locationName,
            ],
        ];
    }
}
