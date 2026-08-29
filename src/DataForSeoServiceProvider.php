<?php

declare(strict_types=1);

namespace Emeq\DataForSeoApi;

use Emeq\DataForSeoApi\Contracts\DataForSeoCredentialResolver;
use Illuminate\Support\ServiceProvider;

final class DataForSeoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->scoped(DataForSeo::class, function (): DataForSeo {
            $resolver = $this->app->make(DataForSeoCredentialResolver::class);

            return new DataForSeo($resolver);
        });
    }
}
