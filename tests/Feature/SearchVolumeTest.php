<?php

declare(strict_types=1);

use Emeq\DataForSeoApi\Exceptions\DataForSeoTaskException;
use Emeq\DataForSeoApi\Http\Request\SearchVolumeRequest;
use Saloon\Http\Faking\MockClient;

it('posts keywords with NL defaults and returns every keyword row', function (): void {
    $rows = [
        ['keyword' => 'theorie examen', 'search_volume' => 12100, 'cpc' => 0.41],
        ['keyword' => 'auto theorie', 'search_volume' => 9900, 'cpc' => 0.37],
    ];
    $mockClient = new MockClient([SearchVolumeRequest::class => taskEnvelope($rows)]);

    $result = dataForSeoWith($mockClient)->searchVolume(['theorie examen', 'auto theorie']);

    expect($result)->toBe($rows);
    $mockClient->assertSent(fn (SearchVolumeRequest $request): bool => $request->resolveEndpoint() === '/v3/keywords_data/google_ads/search_volume/live'
        && $request->body()->all() === [[
            'keywords' => ['theorie examen', 'auto theorie'],
            'location_code' => 2528,
            'language_code' => 'nl',
        ]]);
});

it('lets options override the defaults', function (): void {
    $mockClient = new MockClient([SearchVolumeRequest::class => taskEnvelope([])]);

    dataForSeoWith($mockClient)->searchVolume(['driving test'], ['language_code' => 'en', 'search_partners' => true]);

    $mockClient->assertSent(fn (SearchVolumeRequest $request): bool => $request->body()->all()[0]['language_code'] === 'en'
        && $request->body()->all()[0]['search_partners'] === true);
});

it('throws when the task fails', function (): void {
    $mockClient = new MockClient([SearchVolumeRequest::class => taskEnvelope([], 40501, 'Invalid Field.')]);

    dataForSeoWith($mockClient)->searchVolume(['x']);
})->throws(DataForSeoTaskException::class, '[40501] Invalid Field.');
