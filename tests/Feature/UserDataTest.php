<?php

declare(strict_types=1);

use Emeq\DataForSeoApi\Http\Request\UserDataRequest;
use Saloon\Exceptions\Request\Statuses\UnauthorizedException;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('gets the account user data and returns the first result', function (): void {
    $userData = ['login' => 'login@example.com', 'money' => ['total' => 50.5, 'balance' => 42.5]];
    $mockClient = new MockClient([UserDataRequest::class => taskEnvelope([$userData])]);

    $result = dataForSeoWith($mockClient)->userData();

    expect($result)->toBe($userData);
    $mockClient->assertSent(fn (UserDataRequest $request): bool => $request->resolveEndpoint() === '/v3/appendix/user_data'
        && $request->getMethod()->value === 'GET');
});

it('throws on rejected credentials', function (): void {
    $mockClient = new MockClient([UserDataRequest::class => MockResponse::make(['status_code' => 40100], 401)]);

    dataForSeoWith($mockClient)->userData();
})->throws(UnauthorizedException::class);
