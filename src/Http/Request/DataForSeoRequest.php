<?php

declare(strict_types=1);

namespace Emeq\DataForSeoApi\Http\Request;

use Saloon\Enums\Method;
use Saloon\Http\Request;

abstract class DataForSeoRequest extends Request
{
    protected Method $method = Method::POST;
}
