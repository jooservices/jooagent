<?php

namespace JOOservices\Client\Tests\Doubles;

use JOOservices\Client\Contracts\HttpClientInterface;
use RuntimeException;

class HttpClientStub implements HttpClientInterface
{
    /**
     * @param array<string, string> $responses
     */
    public function __construct(private array $responses)
    {
    }

    public function get(string $url, array $headers = []): string
    {
        if (! array_key_exists($url, $this->responses)) {
            throw new RuntimeException(sprintf('Unexpected URL requested: %s', $url));
        }

        return $this->responses[$url];
    }
}
