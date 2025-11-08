<?php

namespace JOOservices\Client\Dto;

use JOOservices\Client\ValueObjects\Browser;
use JOOservices\Client\ValueObjects\Device;
use JOOservices\Client\ValueObjects\OperatingSystem;
use JOOservices\Client\ValueObjects\UserAgentPattern;

class UserAgentDefinition
{
    public function __construct(
        private OperatingSystem $operatingSystem,
        private Device $device,
        private Browser $browser,
        private UserAgentPattern $pattern,
        private array $tags = []
    ) {
    }

    public function operatingSystem(): OperatingSystem
    {
        return $this->operatingSystem;
    }

    public function device(): Device
    {
        return $this->device;
    }

    public function browser(): Browser
    {
        return $this->browser;
    }

    public function pattern(): UserAgentPattern
    {
        return $this->pattern;
    }

    /**
     * @return string[]
     */
    public function tags(): array
    {
        return $this->tags;
    }
}
