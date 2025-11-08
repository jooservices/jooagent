<?php

namespace JOOservices\Client\Builders;

use JOOservices\Client\Contracts\UserAgentGeneratorInterface;
use JOOservices\Client\Dto\UserAgentSpecification;
use JOOservices\Client\Generators\UserAgentGeneratorFactory;
use JOOservices\Client\ValueObjects\UserAgent;

class UserAgentBuilder
{
    private ?string $operatingSystem = null;
    private ?string $device = null;
    private ?string $browser = null;
    private ?string $operatingSystemVersion = null;
    private ?string $browserVersion = null;
    private ?string $operatingSystemVersionConstraint = null;
    private ?string $browserVersionConstraint = null;
    private array $tags = [];

    private function __construct(private UserAgentGeneratorInterface $generator)
    {
    }

    public static function create(?UserAgentGeneratorInterface $generator = null): self
    {
        return new self($generator ?? UserAgentGeneratorFactory::createDefault());
    }

    public function operatingSystem(string $slug): self
    {
        $clone = clone $this;
        $clone->operatingSystem = $slug;

        return $clone;
    }

    public function device(string $slug): self
    {
        $clone = clone $this;
        $clone->device = $slug;

        return $clone;
    }

    public function browser(string $slug): self
    {
        $clone = clone $this;
        $clone->browser = $slug;

        return $clone;
    }

    public function operatingSystemVersion(string $version): self
    {
        $clone = clone $this;
        $clone->operatingSystemVersion = $version;

        return $clone;
    }

    public function browserVersion(string $version): self
    {
        $clone = clone $this;
        $clone->browserVersion = $version;

        return $clone;
    }

    public function operatingSystemVersionRange(string $constraint): self
    {
        $clone = clone $this;
        $clone->operatingSystemVersionConstraint = $constraint;

        return $clone;
    }

    public function browserVersionRange(string $constraint): self
    {
        $clone = clone $this;
        $clone->browserVersionConstraint = $constraint;

        return $clone;
    }

    /**
     * @param string[] $tags
     */
    public function withTags(array $tags): self
    {
        $clone = clone $this;
        $clone->tags = $tags;

        return $clone;
    }

    public function addTag(string $tag): self
    {
        $clone = clone $this;
        $clone->tags = array_values(array_unique([...$clone->tags, $tag]));

        return $clone;
    }

    public function generate(): UserAgent
    {
        $specification = new UserAgentSpecification(
            operatingSystem: $this->operatingSystem,
            device: $this->device,
            browser: $this->browser,
            operatingSystemVersion: $this->operatingSystemVersion,
            browserVersion: $this->browserVersion,
            operatingSystemVersionConstraint: $this->operatingSystemVersionConstraint,
            browserVersionConstraint: $this->browserVersionConstraint,
            tags: $this->tags
        );

        return $this->generator->generate($specification);
    }
}
