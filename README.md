# emeq/dataforseo-api

Laravel SDK om de [DataForSEO](https://dataforseo.com) API v3 aan te roepen — Saloon v4,
HTTP Basic auth, per-tenant credentials. Gebouwd voor gebruik binnen de
[emeq-hub](https://github.com/yusufkaracaburun/emeq-hub) DataForSeo-provider, maar
bruikbaar in elk Laravel-project.

## Installatie

```bash
composer require emeq/dataforseo-api
```

Voegt automatisch `DataForSeoServiceProvider` en de `DataForSeo`-facade toe via Laravel's
package discovery.

## Credentials

De SDK haalt zelf geen configuratie op — implementeer `DataForSeoCredentialResolver` en
bind 'm in de container:

```php
use Emeq\DataForSeoApi\Contracts\DataForSeoCredentialResolver;
use Emeq\DataForSeoApi\Data\DataForSeoCredentials;

final class YourCredentialResolver implements DataForSeoCredentialResolver
{
    public function resolve(): DataForSeoCredentials
    {
        return new DataForSeoCredentials(
            login: config('services.dataforseo.login'),
            password: config('services.dataforseo.password'),
        );
    }
}
```

```php
// AppServiceProvider
$this->app->bind(DataForSeoCredentialResolver::class, YourCredentialResolver::class);
```

DataForSEO gebruikt HTTP Basic auth (`login:password`) — geen OAuth, geen API-key-header.

## Gebruik

```php
use Emeq\DataForSeoApi\Facades\DataForSeo;

$overview = DataForSeo::domainOverview('example.com');
// of met expliciete locatie/taal (default: 2826 / "nl")
app(\Emeq\DataForSeoApi\DataForSeo::class)->domainOverview(
    domain: 'example.com',
    locationCode: 2826,
    languageCode: 'nl',
);
```

`domainOverview()` retourneert de ruwe `result[0]`-array uit DataForSEO's task-envelope
(`tasks[0].result[0]`) — geen eigen mapping-laag, dat is aan de aanroepende applicatie.

## Endpoints

| Methode | DataForSEO-endpoint | Doel |
|---|---|---|
| `domainOverview()` | `POST /v3/dataforseo_labs/google/domain_rank_overview/live` | Organisch verkeer, keywords, backlinks-samenvatting per domein |

Meer endpoints (keyword research, backlinks, rank tracking, site audit) volgen als losse
methodes op `DataForSeo`/`DataForSeoConnector` — zie
[emeq-hub#83](https://github.com/yusufkaracaburun/emeq-hub/issues/83) voor de context
achter dit package, en de DataForSEO-labs-documentatie voor de volledige API-surface.

## Ontwikkelen

```bash
composer install
composer test      # Pest
composer analyse    # PHPStan/Larastan
composer format      # Pint
```

## Licentie

MIT.
