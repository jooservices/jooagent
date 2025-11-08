<?php

namespace JOOservices\Client\Selectors;

use JOOservices\Client\Contracts\VersionSelectorInterface;
use JOOservices\Client\Exceptions\VersionNotAvailableException;
use JOOservices\Client\Support\VersionComparator;
use JOOservices\Client\ValueObjects\VersionConstraint;
use JOOservices\Client\ValueObjects\VersionPool;

class PreferredRandomVersionSelector implements VersionSelectorInterface
{
    public function select(VersionPool $pool, ?string $preferredVersion = null, ?VersionConstraint $constraint = null): string
    {
        if ($preferredVersion !== null) {
            if (! $pool->contains($preferredVersion)) {
                throw new VersionNotAvailableException(sprintf('Version "%s" is not available.', $preferredVersion));
            }

            return $preferredVersion;
        }

        $versions = $pool->all();

        if ($constraint !== null) {
            $matching = array_values(array_filter(
                $versions,
                static fn (string $version): bool => $constraint->isSatisfiedBy($version)
            ));

            if ($matching === []) {
                throw new VersionNotAvailableException('No versions match the provided constraint.');
            }

            usort($matching, static fn (string $a, string $b): int => VersionComparator::compare($b, $a));

            return $matching[0];
        }

        $index = random_int(0, count($versions) - 1);

        return $versions[$index];
    }
}
