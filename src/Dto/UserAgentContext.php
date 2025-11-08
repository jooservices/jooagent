<?php

namespace JOOservices\Client\Dto;

use JOOservices\Client\ValueObjects\Browser;
use JOOservices\Client\ValueObjects\Device;
use JOOservices\Client\ValueObjects\OperatingSystem;

class UserAgentContext
{
    public function __construct(
        private OperatingSystem $operatingSystem,
        private Device $device,
        private Browser $browser,
        private VersionSelection $versions
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

    public function versions(): VersionSelection
    {
        return $this->versions;
    }

    /**
     * @return array<string, string>
     */
    public function toVariables(): array
    {
        return [
            'os_name' => $this->operatingSystem->name(),
            'os_token' => $this->operatingSystem->token(),
            'os_version' => $this->versions->operatingSystemVersion(),
            'device_name' => $this->device->name(),
            'device_descriptor' => $this->device->descriptor(),
            'browser_name' => $this->browser->name(),
            'browser_token' => $this->browser->token(),
            'browser_version' => $this->versions->browserVersion(),
        ];
    }
}
