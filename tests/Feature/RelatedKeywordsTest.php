<?php

declare(strict_types=1);

use Emeq\DataForSeoApi\Exceptions\DataForSeoTaskException;
use Emeq\DataForSeoApi\Http\Request\RelatedKeywordsRequest;
use Saloon\Http\Faking\MockClient;

it('posts the seed keyword with NL defaults and returns the first result', function (): void {
    $related = ['seed_keyword' => 'theorie', 'items_count' => 1, 'items' => [['keyword_data' => ['keyword' => 'theorie oefenen']]]];
    $mockClient = new MockClient([RelatedKeywordsRequest::class => taskEnvelope([$related])]);

    $result = dataForSeoWith($mockClient)->relatedKeywords('theorie', ['depth' => 2, 'limit' => 50]);

    expect($result)->toBe($related);
    $mockClient->assertSent(fn (RelatedKeywordsRequest $request): bool => $request->resolveEndpoint() === '/v3/dataforseo_labs/google/related_keywords/live'
        && $request->body()->all() === [[
            'keyword' => 'theorie',
            'location_code' => 2528,
            'language_code' => 'nl',
            'depth' => 2,
            'limit' => 50,
        ]]);
});

it('throws when the task fails', function (): void {
    $mockClient = new MockClient([RelatedKeywordsRequest::class => taskEnvelope([], 40501, 'Invalid Field.')]);

    dataForSeoWith($mockClient)->relatedKeywords('x');
})->throws(DataForSeoTaskException::class, '[40501] Invalid Field.');
