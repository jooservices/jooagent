<?php

namespace JOOservices\Client\Dto;

class VersionSelection
{
    public function __construct(
        private string $operatingSystemVersion,
        private string $browserVersion
    ) {
    }

    public function operatingSystemVersion(): string
    {
        return $this->operatingSystemVersion;
    }

    public function browserVersion(): string
    {
        return $this->browserVersion;
    }
}
