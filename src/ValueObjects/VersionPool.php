<?php

namespace JOOservices\Client\ValueObjects;

use InvalidArgumentException;

class VersionPool
{
    /**
     * @param string[] $versions
     */
    public function __construct(private array $versions)
    {
        if ($versions === []) {
            throw new InvalidArgumentException('Version pool cannot be empty.');
        }
    }

    /**
     * @return string[]
     */
    public function all(): array
    {
        return $this->versions;
    }

    public function contains(string $version): bool
    {
        return in_array($version, $this->versions, true);
    }

    public function first(): string
    {
        return $this->versions[0];
    }
}
