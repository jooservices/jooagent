<?php

namespace JOOservices\Client\Tests\Services\BrowserVersions;

use JOOservices\Client\Contracts\BrowserVersionSourceInterface;
use JOOservices\Client\Services\BrowserVersions\BrowserVersionUpdater;
use PHPUnit\Framework\TestCase;

class BrowserVersionUpdaterTest extends TestCase
{
    public function test_it_writes_versions_to_json_file(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'browser_versions_');

        $source = new class implements BrowserVersionSourceInterface {
            public function browser(): string
            {
                return 'chrome';
            }

            public function fetchLatestVersion(): string
            {
                return '999.0.0.0';
            }
        };

        $updater = new BrowserVersionUpdater([$source]);
        $updater->update($path);

        $contents = file_get_contents($path);

        self::assertNotFalse($contents);
        self::assertStringContainsString('"chrome": "999.0.0.0"', $contents);

        unlink($path);
    }
}
