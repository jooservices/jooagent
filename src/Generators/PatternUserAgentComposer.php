<?php

namespace JOOservices\Client\Generators;

use JOOservices\Client\Contracts\UserAgentComposerInterface;
use JOOservices\Client\Dto\UserAgentContext;
use JOOservices\Client\Dto\UserAgentDefinition;
use JOOservices\Client\ValueObjects\UserAgent;

class PatternUserAgentComposer implements UserAgentComposerInterface
{
    public function compose(UserAgentDefinition $definition, UserAgentContext $context): UserAgent
    {
        $variables = $context->toVariables();
        $pattern = $definition->pattern();

        return new UserAgent($pattern->render($variables));
    }
}
