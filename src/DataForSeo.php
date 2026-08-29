<?php

declare(strict_types=1);

namespace Emeq\DataForSeoApi;

use Emeq\DataForSeoApi\Contracts\DataForSeoCredentialResolver;
use Emeq\DataForSeoApi\Data\DomainOverview;
use Emeq\DataForSeoApi\Http\DataForSeoConnector;
use Emeq\DataForSeoApi\Http\Request\DomainOverviewRequest;

final class DataForSeo
{
    private ?DataForSeoConnector $connector = null;

    public function __construct(
        private readonly DataForSeoCredentialResolver $resolver,
    ) {}

    public function connector(): DataForSeoConnector
    {
        return $this->connector ??= new DataForSeoConnector($this->resolver);
    }

    /**
     * @return array<string, mixed>
     */
    public function domainOverview(string $domain): array
    {
        $request = new DomainOverviewRequest($domain);
        $response = $this->connector()->send($request);

        if ($response->failed()) {
            $response->throw();
        }

        $data = $response->json();

        /** @var array<string, mixed>|null $task */
        $task = $data['tasks'][0] ?? null;
        $result = $task['result'][0] ?? [];

        $overview = DomainOverview::fromTaskResult($result);

        return $overview->raw;
    }
}
