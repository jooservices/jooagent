<?php

namespace JOOservices\Client\Tests\ValueObjects;

use InvalidArgumentException;
use JOOservices\Client\ValueObjects\VersionConstraint;
use PHPUnit\Framework\TestCase;

class VersionConstraintTest extends TestCase
{
    public function test_it_accepts_matching_version(): void
    {
        $constraint = VersionConstraint::fromString('>=120.0, <122.0');

        self::assertTrue($constraint->isSatisfiedBy('121.0.6115.86'));
    }

    public function test_it_rejects_non_matching_version(): void
    {
        $constraint = VersionConstraint::fromString('>=120.0, <122.0');

        self::assertFalse($constraint->isSatisfiedBy('122.0.6261.111'));
    }

    public function test_it_throws_for_invalid_expression(): void
    {
        $this->expectException(InvalidArgumentException::class);

        VersionConstraint::fromString('not-supported');
    }

    public function test_it_throws_for_empty_segment(): void
    {
        $this->expectException(InvalidArgumentException::class);

        VersionConstraint::fromString('>=1.0, , <2.0');
    }
}
