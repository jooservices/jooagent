<?php

namespace JOOservices\Client\Generators;

use JOOservices\Client\Contracts\UserAgentDefinitionProviderInterface;
use JOOservices\Client\Contracts\UserAgentGeneratorInterface;
use JOOservices\Client\Data\UserAgentDefinitionProvider;
use JOOservices\Client\Repositories\InMemoryUserAgentRepository;
use JOOservices\Client\Selectors\PreferredRandomVersionSelector;
use JOOservices\Client\ValueObjects\BrowserVersionCatalog;

class UserAgentGeneratorFactory
{
    public static function createDefault(?UserAgentDefinitionProviderInterface $provider = null): UserAgentGeneratorInterface
    {
        $provider ??= new UserAgentDefinitionProvider(self::defaultBrowserVersionCatalog());
        $repository = new InMemoryUserAgentRepository($provider->definitions());
        $versionSelector = new PreferredRandomVersionSelector();
        $composer = new PatternUserAgentComposer();

        return new DefaultUserAgentGenerator($repository, $versionSelector, $composer);
    }

    private static function defaultBrowserVersionCatalog(): BrowserVersionCatalog
    {
        $path = dirname(__DIR__, 2).'/resources/browser_versions.json';

        return new BrowserVersionCatalog($path);
    }
}
