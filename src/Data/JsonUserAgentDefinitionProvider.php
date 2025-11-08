<?php

namespace JOOservices\Client\Data;

use InvalidArgumentException;
use JsonException;
use JOOservices\Client\Contracts\UserAgentDefinitionProviderInterface;
use JOOservices\Client\Dto\UserAgentDefinition;
use JOOservices\Client\ValueObjects\Browser;
use JOOservices\Client\ValueObjects\Device;
use JOOservices\Client\ValueObjects\OperatingSystem;
use JOOservices\Client\ValueObjects\UserAgentPattern;
use JOOservices\Client\ValueObjects\VersionPool;

class JsonUserAgentDefinitionProvider implements UserAgentDefinitionProviderInterface
{
    public function __construct(private string $path)
    {
    }

    public function definitions(): array
    {
        if (! is_file($this->path)) {
            return [];
        }

        $contents = file_get_contents($this->path);

        if ($contents === false) {
            return [];
        }

        try {
            $decoded = json_decode($contents, true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new InvalidArgumentException('Unable to decode JSON user agent definitions: '.$exception->getMessage(), previous: $exception);
        }

        if (! is_array($decoded)) {
            throw new InvalidArgumentException('JSON user agent definition must decode to an array.');
        }

        return array_map([$this, 'hydrateDefinition'], $decoded);
    }

    private function hydrateDefinition(array $definition): UserAgentDefinition
    {
        $operatingSystem = $this->buildOperatingSystem($definition['operating_system'] ?? null);
        $device = $this->buildDevice($definition['device'] ?? null);
        $browser = $this->buildBrowser($definition['browser'] ?? null);
        $pattern = $definition['pattern'] ?? null;

        if (! is_string($pattern) || $pattern === '') {
            throw new InvalidArgumentException('User agent pattern must be a non-empty string.');
        }

        $tags = $this->stringValues($definition['tags'] ?? []);

        return new UserAgentDefinition(
            $operatingSystem,
            $device,
            $browser,
            new UserAgentPattern($pattern),
            $tags
        );
    }

    /**
     * @param mixed $config
     */
    private function buildOperatingSystem(mixed $config): OperatingSystem
    {
        if (! is_array($config)) {
            throw new InvalidArgumentException('Operating system configuration must be an array.');
        }

        $slug = $this->requireString($config, 'slug');
        $name = $this->requireString($config, 'name');
        $token = $this->requireString($config, 'token');
        $versions = $this->stringValues($config['versions'] ?? []);

        return new OperatingSystem($slug, $name, $token, new VersionPool($versions));
    }

    /**
     * @param mixed $config
     */
    private function buildDevice(mixed $config): Device
    {
        if (! is_array($config)) {
            throw new InvalidArgumentException('Device configuration must be an array.');
        }

        $slug = $this->requireString($config, 'slug');
        $name = $this->requireString($config, 'name');
        $descriptor = $this->requireString($config, 'descriptor');
        $type = $config['type'] ?? 'generic';

        if (! is_string($type) || $type === '') {
            throw new InvalidArgumentException('Device type must be a non-empty string when provided.');
        }

        return new Device($slug, $name, $descriptor, $type);
    }

    /**
     * @param mixed $config
     */
    private function buildBrowser(mixed $config): Browser
    {
        if (! is_array($config)) {
            throw new InvalidArgumentException('Browser configuration must be an array.');
        }

        $slug = $this->requireString($config, 'slug');
        $name = $this->requireString($config, 'name');
        $token = $this->requireString($config, 'token');
        $versions = $this->stringValues($config['versions'] ?? []);

        return new Browser($slug, $name, $token, new VersionPool($versions));
    }

    private function requireString(array $config, string $key): string
    {
        $value = $config[$key] ?? null;

        if (! is_string($value) || $value === '') {
            throw new InvalidArgumentException(sprintf('Configuration key "%s" must be a non-empty string.', $key));
        }

        return $value;
    }

    /**
     * @param mixed $values
     * @return string[]
     */
    private function stringValues(mixed $values): array
    {
        if (! is_array($values)) {
            throw new InvalidArgumentException('Versions and tags must be defined as arrays of strings.');
        }

        $result = [];

        foreach ($values as $value) {
            if (! is_string($value) || $value === '') {
                throw new InvalidArgumentException('Versions and tags must contain only non-empty strings.');
            }

            $result[] = $value;
        }

        return $result;
    }
}
