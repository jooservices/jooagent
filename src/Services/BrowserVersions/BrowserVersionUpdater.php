<?php

namespace JOOservices\Client\Services\BrowserVersions;

use JOOservices\Client\Contracts\BrowserVersionSourceInterface;
use RuntimeException;

class BrowserVersionUpdater
{
    /**
     * @param BrowserVersionSourceInterface[] $sources
     */
    public function __construct(private array $sources)
    {
    }

    public function update(string $targetPath): void
    {
        $versions = [];

        foreach ($this->sources as $source) {
            $browser = $source->browser();
            $versions[$browser] = $source->fetchLatestVersion();
        }

        $encoded = json_encode($versions, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        if ($encoded === false) {
            throw new RuntimeException('Failed to encode browser versions as JSON.');
        }

        if (file_put_contents($targetPath, $encoded.PHP_EOL) === false) {
            throw new RuntimeException(sprintf('Failed to write browser versions to "%s".', $targetPath));
        }
    }
}
