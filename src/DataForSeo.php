<?php

declare(strict_types=1);

namespace Emeq\DataForSeoApi;

use Emeq\DataForSeoApi\Contracts\DataForSeoCredentialResolver;
use Emeq\DataForSeoApi\Data\DomainOverview;
use Emeq\DataForSeoApi\Exceptions\DataForSeoTaskException;
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
    public function domainOverview(string $domain, string $locationName = 'Netherlands'): array
    {
        $request = new DomainOverviewRequest($domain, $locationName);
        $response = $this->connector()->send($request);

        if ($response->failed()) {
            $response->throw();
        }

        $data = $response->json();

        /** @var array<string, mixed>|null $task */
        $task = $data['tasks'][0] ?? null;
        $statusCode = $task['status_code'] ?? null;

        if (($data['tasks_error'] ?? 0) > 0 || $statusCode !== 20000) {
            throw new DataForSeoTaskException(
                statusCode: (int) ($statusCode ?? 0),
                statusMessage: (string) ($task['status_message'] ?? 'Unknown DataForSEO task error'),
            );
        }

        $result = $task['result'][0] ?? [];

        $overview = DomainOverview::fromTaskResult($result);

        return $overview->raw;
    }
}
