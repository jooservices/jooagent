<?php

namespace JOOservices\Client\Contracts;

use JOOservices\Client\Dto\UserAgentContext;
use JOOservices\Client\Dto\UserAgentDefinition;
use JOOservices\Client\ValueObjects\UserAgent;

interface UserAgentComposerInterface
{
    public function compose(UserAgentDefinition $definition, UserAgentContext $context): UserAgent;
}
