<?php

namespace JOOservices\Client\Tests;

use JOOservices\Client\Builders\UserAgentBuilder;
use PHPUnit\Framework\TestCase;

class UserAgentBuilderTest extends TestCase
{
    public function test_it_generates_user_agent_without_parameters(): void
    {
        $userAgent = UserAgentBuilder::create()->generate();

        $this->assertStringContainsString('Mozilla/5.0', $userAgent->value());
    }

    public function test_it_supports_fluent_configuration(): void
    {
        $userAgent = UserAgentBuilder::create()
            ->operatingSystem('windows')
            ->device('desktop')
            ->browser('chrome')
            ->operatingSystemVersion('10.0')
            ->browserVersion('121.0.6115.86')
            ->generate();

        $value = $userAgent->value();

        $this->assertStringContainsString('Windows NT 10.0', $value);
        $this->assertStringContainsString('Chrome/121.0.6115.86', $value);
    }

    public function test_it_supports_version_ranges(): void
    {
        $value = UserAgentBuilder::create()
            ->browser('chrome')
            ->browserVersionRange('>=120.0')
            ->generate()
            ->value();

        $this->assertStringContainsString('Chrome/122.0.6261.111', $value);
    }

    public function test_it_supports_operating_system_version_ranges(): void
    {
        $value = UserAgentBuilder::create()
            ->operatingSystem('macos')
            ->operatingSystemVersionRange('>=12.0')
            ->device('desktop')
            ->browser('safari')
            ->browserVersionRange('>=17.0')
            ->generate()
            ->value();

        $this->assertStringContainsString('Intel Mac OS X 13_5', $value);
        $this->assertStringContainsString('Version/17.5', $value);
    }

    public function test_it_filters_by_tags(): void
    {
        $userAgent = UserAgentBuilder::create()
            ->withTags(['bot'])
            ->generate()
            ->value();

        $this->assertStringContainsString('Googlebot/2.1', $userAgent);
    }
}
