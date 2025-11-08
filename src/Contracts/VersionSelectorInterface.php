<?php

namespace JOOservices\Client\Contracts;

use JOOservices\Client\ValueObjects\VersionConstraint;
use JOOservices\Client\ValueObjects\VersionPool;

interface VersionSelectorInterface
{
    public function select(VersionPool $pool, ?string $preferredVersion = null, ?VersionConstraint $constraint = null): string;
}
