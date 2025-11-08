<?php

namespace JOOservices\Client\Support\Http;

use JOOservices\Client\Contracts\HttpClientInterface;
use RuntimeException;

class StreamHttpClient implements HttpClientInterface
{
    public function __construct(private int $timeout = 10)
    {
    }

    public function get(string $url, array $headers = []): string
    {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => $this->timeout,
                'header' => $this->formatHeaders($headers),
            ],
        ]);

        $result = @file_get_contents($url, false, $context);

        if ($result === false) {
            $error = error_get_last();

            throw new RuntimeException(sprintf('Failed to fetch URL "%s": %s', $url, $error['message'] ?? 'unknown error'));
        }

        return $result;
    }

    private function formatHeaders(array $headers): string
    {
        if ($headers === []) {
            return '';
        }

        return implode("\r\n", array_map(
            static fn (string $key, string $value): string => sprintf('%s: %s', $key, $value),
            array_keys($headers),
            $headers
        ));
    }
}
