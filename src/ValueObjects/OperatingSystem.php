<?php

namespace JOOservices\Client\ValueObjects;

class OperatingSystem
{
    public function __construct(
        private string $slug,
        private string $name,
        private string $token,
        private VersionPool $versions
    ) {
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function token(): string
    {
        return $this->token;
    }

    public function versions(): VersionPool
    {
        return $this->versions;
    }
}
