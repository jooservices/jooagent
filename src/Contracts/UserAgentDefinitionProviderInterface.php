<?php

namespace JOOservices\Client\Contracts;

use JOOservices\Client\Dto\UserAgentDefinition;

interface UserAgentDefinitionProviderInterface
{
    /**
     * @return UserAgentDefinition[]
     */
    public function definitions(): array;
}
