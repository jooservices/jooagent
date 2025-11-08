<?php

namespace JOOservices\Client\Dto;

class UserAgentSpecification
{
    public function __construct(
        private ?string $operatingSystem = null,
        private ?string $device = null,
        private ?string $browser = null,
        private ?string $operatingSystemVersion = null,
        private ?string $browserVersion = null,
        private ?string $operatingSystemVersionConstraint = null,
        private ?string $browserVersionConstraint = null,
        private array $tags = []
    ) {
    }

    public function operatingSystem(): ?string
    {
        return $this->operatingSystem;
    }

    public function device(): ?string
    {
        return $this->device;
    }

    public function browser(): ?string
    {
        return $this->browser;
    }

    public function operatingSystemVersion(): ?string
    {
        return $this->operatingSystemVersion;
    }

    public function browserVersion(): ?string
    {
        return $this->browserVersion;
    }

    public function operatingSystemVersionConstraint(): ?string
    {
        return $this->operatingSystemVersionConstraint;
    }

    public function browserVersionConstraint(): ?string
    {
        return $this->browserVersionConstraint;
    }

    /**
     * @return string[]
     */
    public function tags(): array
    {
        return $this->tags;
    }
}
