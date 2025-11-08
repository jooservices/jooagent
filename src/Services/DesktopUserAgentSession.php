<?php

namespace JOOservices\Client\Services;

use JOOservices\Client\Builders\UserAgentBuilder;
use JOOservices\Client\Contracts\UserAgentGeneratorInterface;
use JOOservices\Client\ValueObjects\UserAgent;

class DesktopUserAgentSession
{
    /**
     * @var array<string, UserAgent>
     */
    private array $cache = [];

    public function __construct(private UserAgentGeneratorInterface $generator)
    {
    }

    public function forRequest(string $requestId): UserAgent
    {
        if (isset($this->cache[$requestId])) {
            return $this->cache[$requestId];
        }

        $userAgent = UserAgentBuilder::create($this->generator)
            ->withTags(['desktop'])
            ->generate();

        return $this->cache[$requestId] = $userAgent;
    }

    public function forget(string $requestId): void
    {
        unset($this->cache[$requestId]);
    }

    public function clear(): void
    {
        $this->cache = [];
    }
}
