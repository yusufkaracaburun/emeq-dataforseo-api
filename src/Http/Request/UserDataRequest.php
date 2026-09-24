<?php

declare(strict_types=1);

namespace Emeq\DataForSeoApi\Http\Request;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class UserDataRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/v3/appendix/user_data';
    }
}
