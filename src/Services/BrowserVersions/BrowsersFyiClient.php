<?php

namespace JOOservices\Client\Services\BrowserVersions;

use InvalidArgumentException;
use JOOservices\Client\Contracts\HttpClientInterface;
use JsonException;

class BrowsersFyiClient
{
    private const DEFAULT_ENDPOINT = 'https://www.browsers.fyi/api/';

    /**
     * @var array<string, array<string, mixed>>|null
     */
    private ?array $cache = null;

    public function __construct(
        private HttpClientInterface $client,
        private string $endpoint = self::DEFAULT_ENDPOINT
    ) {
    }

    public function latestVersion(string $browserKey): string
    {
        $data = $this->data();

        if (! isset($data[$browserKey]['version']) || ! is_string($data[$browserKey]['version'])) {
            throw new InvalidArgumentException(sprintf('Browser key "%s" not found in browsers.fyi response.', $browserKey));
        }

        return $data[$browserKey]['version'];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function data(): array
    {
        if ($this->cache !== null) {
            return $this->cache;
        }

        $response = $this->client->get($this->endpoint, [
            'User-Agent' => 'JOOAgent/1.0 (+https://jooservices.example)',
            'Accept' => 'application/json',
        ]);

        try {
            $decoded = json_decode($response, true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new InvalidArgumentException('Unable to parse browsers.fyi response: '.$exception->getMessage(), previous: $exception);
        }

        if (! is_array($decoded)) {
            throw new InvalidArgumentException('Unexpected response structure from browsers.fyi API.');
        }

        $this->cache = $decoded;

        return $this->cache;
    }
}
