<?php

namespace JOOservices\Client\Tests\Support;

use JOOservices\Client\Support\VersionComparator;
use PHPUnit\Framework\TestCase;

class VersionComparatorTest extends TestCase
{
    public function test_compare(): void
    {
        $examples = [
            ['1.0.0', '1.0.0', 0],
            ['1.0.1', '1.0.0', 1],
            ['1.0', '1.0.1', -1],
            ['10_15_7', '10_14_6', 1],
            ['121.0.6115.86', '122.0.6261.111', -1],
            ['alpha', 'beta', -1],
        ];

        foreach ($examples as [$left, $right, $expected]) {
            self::assertSame($expected, VersionComparator::compare($left, $right));
        }
    }
}
