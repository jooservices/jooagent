<?php

namespace JOOservices\Client\Generators;

use JOOservices\Client\Contracts\UserAgentComposerInterface;
use JOOservices\Client\Contracts\UserAgentGeneratorInterface;
use JOOservices\Client\Contracts\UserAgentRepositoryInterface;
use JOOservices\Client\Contracts\VersionSelectorInterface;
use JOOservices\Client\Dto\UserAgentContext;
use JOOservices\Client\Dto\UserAgentSpecification;
use JOOservices\Client\Dto\VersionSelection;
use JOOservices\Client\Exceptions\UserAgentNotFoundException;
use JOOservices\Client\ValueObjects\UserAgent;
use JOOservices\Client\ValueObjects\VersionConstraint;

class DefaultUserAgentGenerator implements UserAgentGeneratorInterface
{
    public function __construct(
        private UserAgentRepositoryInterface $repository,
        private VersionSelectorInterface $versionSelector,
        private UserAgentComposerInterface $composer
    ) {
    }

    public function generate(UserAgentSpecification $specification): UserAgent
    {
        $definitions = $this->repository->matching($specification);

        if ($definitions === []) {
            throw new UserAgentNotFoundException('No user agent definition matched the provided specification.');
        }

        $definition = $definitions[0];

        $osConstraint = $specification->operatingSystemVersionConstraint();
        $browserConstraint = $specification->browserVersionConstraint();

        $osVersion = $this->versionSelector->select(
            $definition->operatingSystem()->versions(),
            $specification->operatingSystemVersion(),
            $osConstraint !== null ? VersionConstraint::fromString($osConstraint) : null
        );

        $browserVersion = $this->versionSelector->select(
            $definition->browser()->versions(),
            $specification->browserVersion(),
            $browserConstraint !== null ? VersionConstraint::fromString($browserConstraint) : null
        );

        $context = new UserAgentContext(
            $definition->operatingSystem(),
            $definition->device(),
            $definition->browser(),
            new VersionSelection($osVersion, $browserVersion)
        );

        return $this->composer->compose($definition, $context);
    }
}
