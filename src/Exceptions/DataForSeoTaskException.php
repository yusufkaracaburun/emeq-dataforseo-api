<?php

declare(strict_types=1);

namespace Emeq\DataForSeoApi\Exceptions;

use RuntimeException;

final class DataForSeoTaskException extends RuntimeException
{
    public function __construct(
        public readonly int $statusCode,
        public readonly string $statusMessage,
    ) {
        parent::__construct("DataForSEO task failed: [{$statusCode}] {$statusMessage}");
    }
}
