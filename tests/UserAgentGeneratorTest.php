<?php

namespace JOOservices\Client\Tests;

use JOOservices\Client\Dto\UserAgentSpecification;
use JOOservices\Client\Exceptions\UserAgentNotFoundException;
use JOOservices\Client\Exceptions\VersionNotAvailableException;
use JOOservices\Client\Generators\UserAgentGeneratorFactory;
use PHPUnit\Framework\TestCase;

class UserAgentGeneratorTest extends TestCase
{
    public function test_it_generates_user_agent_for_specific_versions(): void
    {
        $generator = UserAgentGeneratorFactory::createDefault();

        $specification = new UserAgentSpecification(
            operatingSystem: 'windows',
            device: 'desktop',
            browser: 'chrome',
            operatingSystemVersion: '10.0',
            browserVersion: '121.0.6115.86'
        );

        $userAgent = $generator->generate($specification)->value();

        $this->assertStringContainsString('Windows NT 10.0', $userAgent);
        $this->assertStringContainsString('Win64; x64', $userAgent);
        $this->assertStringContainsString('Chrome/121.0.6115.86', $userAgent);
    }

    public function test_it_throws_when_definition_missing(): void
    {
        $this->expectException(UserAgentNotFoundException::class);

        $generator = UserAgentGeneratorFactory::createDefault();
        $specification = new UserAgentSpecification(operatingSystem: 'nonexistent-os');

        $generator->generate($specification);
    }

    public function test_it_throws_when_version_not_available(): void
    {
        $this->expectException(VersionNotAvailableException::class);

        $generator = UserAgentGeneratorFactory::createDefault();

        $specification = new UserAgentSpecification(
            operatingSystem: 'windows',
            device: 'desktop',
            browser: 'chrome',
            operatingSystemVersion: '9.0'
        );

        $generator->generate($specification);
    }

    public function test_it_resolves_highest_version_matching_constraint(): void
    {
        $generator = UserAgentGeneratorFactory::createDefault();

        $specification = new UserAgentSpecification(
            operatingSystem: 'windows',
            device: 'desktop',
            browser: 'chrome',
            browserVersionConstraint: '>=120.0,<122.0'
        );

        $userAgent = $generator->generate($specification)->value();

        $this->assertStringContainsString('Chrome/121.0.6115.86', $userAgent);
    }

    public function test_it_respects_operating_system_version_constraint(): void
    {
        $generator = UserAgentGeneratorFactory::createDefault();

        $specification = new UserAgentSpecification(
            operatingSystem: 'macos',
            device: 'desktop',
            browser: 'safari',
            operatingSystemVersionConstraint: '>=12.0'
        );

        $userAgent = $generator->generate($specification)->value();

        $this->assertStringContainsString('Intel Mac OS X 13_5', $userAgent);
    }

    public function test_it_throws_when_constraint_is_not_matched(): void
    {
        $this->expectException(VersionNotAvailableException::class);

        $generator = UserAgentGeneratorFactory::createDefault();

        $specification = new UserAgentSpecification(
            operatingSystem: 'windows',
            device: 'desktop',
            browser: 'chrome',
            browserVersionConstraint: '>=130.0'
        );

        $generator->generate($specification);
    }

    public function test_it_filters_by_tags(): void
    {
        $generator = UserAgentGeneratorFactory::createDefault();

        $specification = new UserAgentSpecification(tags: ['console']);

        $userAgent = $generator->generate($specification)->value();

        $this->assertStringContainsString('PlayStationBrowser', $userAgent);
    }
}
