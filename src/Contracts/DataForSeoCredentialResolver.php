<?php

declare(strict_types=1);

namespace Emeq\DataForSeoApi\Contracts;

use Emeq\DataForSeoApi\Data\DataForSeoCredentials;

interface DataForSeoCredentialResolver
{
    public function resolve(): DataForSeoCredentials;
}
