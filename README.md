# JOO Agent

Generate realistic user agent strings with SOLID-friendly components and configurable catalogs.

## Features

- Build user agent strings by selecting operating system, device, browser, and versions.
- Fluent builder API (`UserAgentBuilder`) for chainable configuration or random defaults.
- Specification + repository design for substituting storage backends.
- Automatic version resolution with explicit version, semantic constraints, or random selection.
- Tag-based filtering (desktop, mobile, bot, console, etc.).
- Pluggable definition providers (in-memory defaults, JSON catalogs, custom implementations).
- CLI to refresh browser versions from [browsers.fyi](https://www.browsers.fyi/?utm_source=chatgpt.com).
- Desktop session helper ensures retries reuse the same desktop user agent.
- Comprehensive test suite (`vendor/bin/phpunit`).

## Installation

```bash
composer require jooservices/jooagent
```

> Requires PHP 8.4+

## Quick Start

```php
use JOOservices\Client\Dto\UserAgentSpecification;
use JOOservices\Client\Generators\UserAgentGeneratorFactory;

$generator = UserAgentGeneratorFactory::createDefault();

$specification = new UserAgentSpecification(
    operatingSystem: 'windows',
    device: 'desktop',
    browser: 'chrome',
    operatingSystemVersion: '11.0',
    browserVersion: '122.0.6261.111'
);

$userAgent = $generator->generate($specification);

echo $userAgent->value();
```

Or use the builder:

```php
use JOOservices\Client\Builders\UserAgentBuilder;

$agent = UserAgentBuilder::create()
    ->operatingSystem('macos')
    ->device('desktop')
    ->browser('safari')
    ->browserVersionRange('>=17.0')
    ->generate();

echo $agent;
```

## Desktop Request Sessions

Use `DesktopUserAgentSession` when you need a random desktop user agent per request, but the same value across retries:

```php
use JOOservices\Client\Services\DesktopUserAgentSession;
use JOOservices\Client\Generators\UserAgentGeneratorFactory;

$session = new DesktopUserAgentSession(UserAgentGeneratorFactory::createDefault());

// First attempt generates a random desktop user agent
$userAgent = $session->forRequest($requestId)->value();

// Subsequent retries for the same request re-use the cached value
$retryUserAgent = $session->forRequest($requestId)->value();
```

## Updating Browser Versions

```
vendor/bin/update-browser-versions
```

Fetches current releases from browsers.fyi and rewrites `resources/browser_versions.json`. This keeps version pools aligned with the latest stable browser releases.

## Testing

```bash
composer test
```

Or run PHPUnit directly:

```bash
vendor/bin/phpunit tests
```

## Contributing

1. Fork the repository and create your branch (`git checkout -b feature/your-feature`).
2. Run tests (`composer test`) and ensure coding standards pass (`composer lint`).
3. Submit a pull request.

## License

Proprietary – contact JOOservices for licensing details.
