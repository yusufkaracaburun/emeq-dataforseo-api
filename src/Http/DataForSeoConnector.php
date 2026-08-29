<?php

declare(strict_types=1);

namespace Emeq\DataForSeoApi\Http;

use Emeq\DataForSeoApi\Contracts\DataForSeoCredentialResolver;
use Saloon\Contracts\Authenticator;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Auth\BasicAuthenticator;
use Saloon\Http\Connector;
use Saloon\Http\Request;

class DataForSeoConnector extends Connector
{
    private readonly string $login;

    private readonly string $password;

    public function __construct(
        DataForSeoCredentialResolver $resolver,
        private readonly int $timeoutSeconds = 60,
        public ?int $tries = 2,
        public ?int $retryInterval = 250,
    ) {
        $creds = $resolver->resolve();
        $this->login = $creds->login;
        $this->password = $creds->password;
    }

    public function resolveBaseUrl(): string
    {
        return 'https://api.dataforseo.com';
    }

    /**
     * HTTP Basic auth via Saloon's built-in authenticator.
     */
    protected function defaultAuth(): ?Authenticator
    {
        return new BasicAuthenticator($this->login, $this->password);
    }

    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    protected function defaultConfig(): array
    {
        return [
            'timeout' => $this->timeoutSeconds,
        ];
    }

    public function handleRetry(FatalRequestException|RequestException $exception, Request $request): bool
    {
        if ($exception instanceof FatalRequestException) {
            return true;
        }

        return in_array($exception->getResponse()->status(), [429, 500, 502, 503, 504], true);
    }
}
