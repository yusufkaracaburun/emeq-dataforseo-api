<?php

declare(strict_types=1);

use Emeq\DataForSeoApi\DataForSeo;
use Emeq\DataForSeoApi\Tests\Support\StaticCredentialResolver;
use Emeq\DataForSeoApi\Tests\TestCase;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

uses(TestCase::class)->in(__DIR__);

function dataForSeoWith(MockClient $mockClient): DataForSeo
{
    $client = new DataForSeo(new StaticCredentialResolver);
    $client->connector()->withMockClient($mockClient);

    return $client;
}

/**
 * @param  array<int, mixed>  $result
 */
function taskEnvelope(array $result, int $statusCode = 20000, string $statusMessage = 'Ok.'): MockResponse
{
    return MockResponse::make([
        'status_code' => 20000,
        'status_message' => 'Ok.',
        'tasks_count' => 1,
        'tasks_error' => $statusCode === 20000 ? 0 : 1,
        'tasks' => [[
            'status_code' => $statusCode,
            'status_message' => $statusMessage,
            'result' => $statusCode === 20000 ? $result : null,
        ]],
    ]);
}
