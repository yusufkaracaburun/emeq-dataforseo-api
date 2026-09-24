<?php

declare(strict_types=1);

namespace Emeq\DataForSeoApi;

use Emeq\DataForSeoApi\Contracts\DataForSeoCredentialResolver;
use Emeq\DataForSeoApi\Data\BacklinksSummary;
use Emeq\DataForSeoApi\Data\DomainOverview;
use Emeq\DataForSeoApi\Exceptions\DataForSeoTaskException;
use Emeq\DataForSeoApi\Http\DataForSeoConnector;
use Emeq\DataForSeoApi\Http\Request\BacklinksSummaryRequest;
use Emeq\DataForSeoApi\Http\Request\DomainOverviewRequest;
use Emeq\DataForSeoApi\Http\Request\RelatedKeywordsRequest;
use Emeq\DataForSeoApi\Http\Request\SearchVolumeRequest;
use Emeq\DataForSeoApi\Http\Request\SerpOrganicRequest;
use Saloon\Http\Request;

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
        $task = $this->firstTask(new DomainOverviewRequest($domain, $locationName));

        return DomainOverview::fromTaskResult($task['result'][0] ?? [])->raw;
    }

    /**
     * @param  array<string, mixed>  $extra
     * @return array<string, mixed>
     */
    public function backlinksSummary(string $target, array $extra = []): array
    {
        $task = $this->firstTask(new BacklinksSummaryRequest($target, $extra));

        return BacklinksSummary::fromTaskResult($task['result'][0] ?? [])->raw;
    }

    /**
     * @param  list<string>  $keywords
     * @param  array<string, mixed>  $options
     * @return list<array<string, mixed>>
     */
    public function searchVolume(array $keywords, array $options = []): array
    {
        return $this->firstTask(new SearchVolumeRequest($keywords, $options))['result'] ?? [];
    }

    /**
     * @param  array<string, mixed>  $options
     * @return array<string, mixed>
     */
    public function serpOrganic(string $keyword, array $options = []): array
    {
        return $this->firstTask(new SerpOrganicRequest($keyword, $options))['result'][0] ?? [];
    }

    /**
     * @param  array<string, mixed>  $options
     * @return array<string, mixed>
     */
    public function relatedKeywords(string $keyword, array $options = []): array
    {
        return $this->firstTask(new RelatedKeywordsRequest($keyword, $options))['result'][0] ?? [];
    }

    /**
     * Task-level failures arrive as HTTP 200 with tasks[0].status_code != 20000.
     *
     * @return array<string, mixed>
     */
    private function firstTask(Request $request): array
    {
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

        return $task;
    }
}
