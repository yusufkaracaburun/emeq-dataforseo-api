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
| `backlinksSummary()` | `POST /v3/backlinks/summary/live` | Backlinks en verwijzende domeinen per target |
| `searchVolume()` | `POST /v3/keywords_data/google_ads/search_volume/live` | Zoekvolume per keyword (max 1000 per call). Geeft `result` terug, één rij per keyword |
| `serpOrganic()` | `POST /v3/serp/google/organic/live/advanced` | Organische SERP met features (PAA, AI Overview, local pack) |
| `relatedKeywords()` | `POST /v3/dataforseo_labs/google/related_keywords/live` | Gerelateerde zoektermen met volume |

De drie keyword-/SERP-methodes gebruiken standaard `location_code` 2528 en `language_code`
`nl`. De tweede parameter `$options` wordt in de task gemerged en overschrijft die defaults
(bijvoorbeeld `['depth' => 20]` bij `serpOrganic()`). Google Ads Live staat maximaal
12 requests per minuut per account toe.

Meer endpoints (rank tracking, site audit) volgen als losse
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
