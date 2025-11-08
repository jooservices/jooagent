<?php

namespace JOOservices\Client\Tests\Selectors;

use JOOservices\Client\Exceptions\VersionNotAvailableException;
use JOOservices\Client\Selectors\PreferredRandomVersionSelector;
use JOOservices\Client\ValueObjects\VersionConstraint;
use JOOservices\Client\ValueObjects\VersionPool;
use PHPUnit\Framework\TestCase;

class PreferredRandomVersionSelectorTest extends TestCase
{
    public function test_it_returns_preferred_version(): void
    {
        $selector = new PreferredRandomVersionSelector();
        $pool = new VersionPool(['1.0', '2.0']);

        self::assertSame('2.0', $selector->select($pool, '2.0'));
    }

    public function test_it_selects_highest_version_matching_constraint(): void
    {
        $selector = new PreferredRandomVersionSelector();
        $pool = new VersionPool(['1.0', '1.5', '2.0']);
        $constraint = VersionConstraint::fromString('>=1.0,<2.0');

        self::assertSame('1.5', $selector->select($pool, constraint: $constraint));
    }

    public function test_it_throws_when_constraint_not_matched(): void
    {
        $this->expectException(VersionNotAvailableException::class);

        $selector = new PreferredRandomVersionSelector();
        $pool = new VersionPool(['1.0', '1.5']);
        $constraint = VersionConstraint::fromString('>=2.0');

        $selector->select($pool, constraint: $constraint);
    }
}
