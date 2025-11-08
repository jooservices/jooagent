#!/usr/bin/env php
<?php

declare(strict_types=1);

use JOOservices\Client\Contracts\BrowserVersionSourceInterface;
use JOOservices\Client\Services\BrowserVersions\BrowserVersionUpdater;
use JOOservices\Client\Services\BrowserVersions\BrowsersFyiClient;
use JOOservices\Client\Services\BrowserVersions\BrowsersFyiVersionSource;
use JOOservices\Client\Support\Http\StreamHttpClient;

require __DIR__.'/../vendor/autoload.php';

$httpClient = new StreamHttpClient();
$browsersFyi = new BrowsersFyiClient($httpClient);

/** @var BrowserVersionSourceInterface[] $sources */
$sources = [
    new BrowsersFyiVersionSource($browsersFyi, 'chrome'),
    new BrowsersFyiVersionSource($browsersFyi, 'firefox'),
    new BrowsersFyiVersionSource($browsersFyi, 'safari'),
];

$updater = new BrowserVersionUpdater($sources);
$target = realpath(__DIR__.'/../resources') ?: __DIR__.'/../resources';
$path = $target.'/browser_versions.json';

try {
    $updater->update($path);
    fwrite(STDOUT, sprintf("Browser versions updated in %s\n", $path));
    exit(0);
} catch (Throwable $exception) {
    fwrite(STDERR, sprintf("Error: %s\n", $exception->getMessage()));
    exit(1);
}
