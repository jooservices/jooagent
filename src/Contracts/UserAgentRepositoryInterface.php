<?php

namespace JOOservices\Client\Contracts;

use JOOservices\Client\Dto\UserAgentDefinition;
use JOOservices\Client\Dto\UserAgentSpecification;

/**
 * @return UserAgentDefinition[]
 */
interface UserAgentRepositoryInterface
{
    public function matching(UserAgentSpecification $specification): array;
}
