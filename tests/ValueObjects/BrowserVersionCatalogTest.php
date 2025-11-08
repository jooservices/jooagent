<?php

namespace JOOservices\Client\Tests\ValueObjects;

use InvalidArgumentException;
use JOOservices\Client\ValueObjects\BrowserVersionCatalog;
use PHPUnit\Framework\TestCase;

class BrowserVersionCatalogTest extends TestCase
{
    public function test_it_loads_versions_from_file(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'browser_catalog_');
        file_put_contents($path, json_encode(['chrome' => '123.0'], JSON_THROW_ON_ERROR));

        $catalog = new BrowserVersionCatalog($path);

        self::assertSame('123.0', $catalog->latest('chrome'));

        unlink($path);
    }

    public function test_it_throws_for_invalid_json(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'browser_catalog_invalid_');
        file_put_contents($path, '{not-json');

        $this->expectException(InvalidArgumentException::class);

        try {
            new BrowserVersionCatalog($path);
        } finally {
            unlink($path);
        }
    }
}
