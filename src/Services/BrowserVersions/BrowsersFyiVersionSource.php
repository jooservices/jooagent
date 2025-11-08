<?php

namespace JOOservices\Client\Services\BrowserVersions;

use JOOservices\Client\Contracts\BrowserVersionSourceInterface;

class BrowsersFyiVersionSource implements BrowserVersionSourceInterface
{
    public function __construct(
        private BrowsersFyiClient $client,
        private string $browserKey
    ) {
    }

    public function browser(): string
    {
        return $this->browserKey;
    }

    public function fetchLatestVersion(): string
    {
        return $this->client->latestVersion($this->browserKey);
    }
}
