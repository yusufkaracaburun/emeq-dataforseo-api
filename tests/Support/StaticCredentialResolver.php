<?php

declare(strict_types=1);

namespace Emeq\DataForSeoApi\Tests\Support;

use Emeq\DataForSeoApi\Contracts\DataForSeoCredentialResolver;
use Emeq\DataForSeoApi\Data\DataForSeoCredentials;

final readonly class StaticCredentialResolver implements DataForSeoCredentialResolver
{
    public function resolve(): DataForSeoCredentials
    {
        return new DataForSeoCredentials(login: 'login', password: 'secret');
    }
}
