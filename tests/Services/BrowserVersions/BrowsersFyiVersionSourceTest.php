<?php

namespace JOOservices\Client\Tests\Services\BrowserVersions;

use InvalidArgumentException;
use JOOservices\Client\Contracts\HttpClientInterface;
use JOOservices\Client\Services\BrowserVersions\BrowsersFyiClient;
use JOOservices\Client\Services\BrowserVersions\BrowsersFyiVersionSource;
use JOOservices\Client\Tests\Doubles\HttpClientStub;
use PHPUnit\Framework\TestCase;

class BrowsersFyiVersionSourceTest extends TestCase
{
    private const RESPONSE = [
        'chrome' => ['version' => '142'],
        'firefox' => ['version' => '144'],
    ];

    public function test_it_returns_latest_version_for_browser_key(): void
    {
        $client = new BrowsersFyiClient(new HttpClientStub([
            'https://www.browsers.fyi/api/' => json_encode(self::RESPONSE, JSON_THROW_ON_ERROR),
        ]));
        $source = new BrowsersFyiVersionSource($client, 'chrome');

        self::assertSame('142', $source->fetchLatestVersion());
    }

    public function test_it_throws_when_browser_not_present(): void
    {
        $client = new BrowsersFyiClient(new HttpClientStub([
            'https://www.browsers.fyi/api/' => json_encode(self::RESPONSE, JSON_THROW_ON_ERROR),
        ]));
        $source = new BrowsersFyiVersionSource($client, 'safari');

        $this->expectException(InvalidArgumentException::class);
        $source->fetchLatestVersion();
    }

    public function test_it_caches_response_across_sources(): void
    {
        $stub = new class() implements HttpClientInterface {
            public int $calls = 0;
            private string $response;

            public function __construct()
            {
                $this->response = json_encode([
                    'chrome' => ['version' => '142'],
                    'firefox' => ['version' => '144'],
                ], JSON_THROW_ON_ERROR);
            }

            public function get(string $url, array $headers = []): string
            {
                if ($url !== 'https://www.browsers.fyi/api/') {
                    throw new \RuntimeException('Unexpected URL: '.$url);
                }

                $this->calls++;

                return $this->response;
            }
        };

        $client = new BrowsersFyiClient($stub);

        $chrome = new BrowsersFyiVersionSource($client, 'chrome');
        $firefox = new BrowsersFyiVersionSource($client, 'firefox');

        $chrome->fetchLatestVersion();
        $firefox->fetchLatestVersion();

        self::assertSame(1, $stub->calls, 'Expected browsers.fyi API to be fetched only once.');
    }
}
