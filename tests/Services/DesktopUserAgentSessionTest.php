<?php

namespace JOOservices\Client\Tests\Services;

use JOOservices\Client\Contracts\UserAgentGeneratorInterface;
use JOOservices\Client\Dto\UserAgentSpecification;
use JOOservices\Client\Generators\UserAgentGeneratorFactory;
use JOOservices\Client\Services\DesktopUserAgentSession;
use JOOservices\Client\ValueObjects\UserAgent;
use PHPUnit\Framework\TestCase;

class DesktopUserAgentSessionTest extends TestCase
{
    public function test_it_returns_same_user_agent_for_retries(): void
    {
        $session = new DesktopUserAgentSession($this->stubGenerator());

        $first = $session->forRequest('request-1')->value();
        $second = $session->forRequest('request-1')->value();

        self::assertSame($first, $second);
    }

    public function test_it_generates_desktop_user_agents(): void
    {
        $session = new DesktopUserAgentSession(UserAgentGeneratorFactory::createDefault());

        $userAgent = $session->forRequest('request-desktop')->value();

        self::assertStringContainsString('Mozilla/5.0', $userAgent);
        self::assertStringNotContainsString('Mobile', $userAgent);
    }

    public function test_forget_allows_new_user_agent(): void
    {
        $session = new DesktopUserAgentSession($this->stubGenerator());

        $first = $session->forRequest('request-2')->value();
        $session->forget('request-2');
        $second = $session->forRequest('request-2')->value();
        $third = $session->forRequest('request-2')->value();

        self::assertNotSame($first, $second);
        self::assertSame($second, $third);
    }

    private function stubGenerator(): UserAgentGeneratorInterface
    {
        $counter = 0;

        return new class($counter) implements UserAgentGeneratorInterface {
            public function __construct(private int &$counter)
            {
            }

            public function generate(UserAgentSpecification $specification): UserAgent
            {
                $this->counter++;

                return new UserAgent('Stub-Desktop-'.$this->counter);
            }
        };
    }
}
