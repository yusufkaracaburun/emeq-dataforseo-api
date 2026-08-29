<?php

declare(strict_types=1);

namespace Emeq\DataForSeoApi\Data;

final readonly class DomainOverview
{
    /**
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public array $raw,
    ) {}

    public static function fromTaskResult(array $result): self
    {
        return new self($result);
    }
}
