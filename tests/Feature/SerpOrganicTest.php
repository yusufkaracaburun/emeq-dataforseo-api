<?php

declare(strict_types=1);

use Emeq\DataForSeoApi\Exceptions\DataForSeoTaskException;
use Emeq\DataForSeoApi\Http\Request\SerpOrganicRequest;
use Saloon\Http\Faking\MockClient;

it('posts the keyword with NL defaults and returns the first result', function (): void {
    $serp = ['keyword' => 'theorie examen', 'items_count' => 1, 'items' => [['type' => 'organic', 'rank_absolute' => 1]]];
    $mockClient = new MockClient([SerpOrganicRequest::class => taskEnvelope([$serp])]);

    $result = dataForSeoWith($mockClient)->serpOrganic('theorie examen', ['load_async_ai_overview' => true]);

    expect($result)->toBe($serp);
    $mockClient->assertSent(fn (SerpOrganicRequest $request): bool => $request->resolveEndpoint() === '/v3/serp/google/organic/live/advanced'
        && $request->body()->all() === [[
            'keyword' => 'theorie examen',
            'location_code' => 2528,
            'language_code' => 'nl',
            'load_async_ai_overview' => true,
        ]]);
});

it('throws when the task fails', function (): void {
    $mockClient = new MockClient([SerpOrganicRequest::class => taskEnvelope([], 40200, 'Payment Required.')]);

    dataForSeoWith($mockClient)->serpOrganic('x');
})->throws(DataForSeoTaskException::class, '[40200] Payment Required.');
