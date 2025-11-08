<?php

namespace JOOservices\Client\ValueObjects;

use InvalidArgumentException;
use JsonException;

class BrowserVersionCatalog
{
    /**
     * @var array<string, string>
     */
    private array $versions = [];

    public function __construct(private string $path)
    {
        if (! is_file($this->path)) {
            return;
        }

        $contents = file_get_contents($this->path);

        if ($contents === false || trim($contents) === '') {
            return;
        }

        try {
            $decoded = json_decode($contents, true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new InvalidArgumentException('Unable to decode browser version catalog: '.$exception->getMessage(), previous: $exception);
        }

        if (! is_array($decoded)) {
            throw new InvalidArgumentException('Browser version catalog must be an object map.');
        }

        foreach ($decoded as $browser => $version) {
            if (! is_string($browser) || ! is_string($version) || $version === '') {
                throw new InvalidArgumentException('Browser version catalog entries must map strings to non-empty strings.');
            }

            $this->versions[$browser] = $version;
        }
    }

    public function latest(string $browser): ?string
    {
        return $this->versions[$browser] ?? null;
    }

    public function path(): string
    {
        return $this->path;
    }

    /**
     * @return array<string, string>
     */
    public function all(): array
    {
        return $this->versions;
    }
}
