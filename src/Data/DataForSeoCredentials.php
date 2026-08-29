<?php

declare(strict_types=1);

namespace Emeq\DataForSeoApi\Data;

final readonly class DataForSeoCredentials
{
    public function __construct(
        public string $login,
        public string $password,
    ) {}
}
