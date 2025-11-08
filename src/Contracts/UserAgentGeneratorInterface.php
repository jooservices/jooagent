<?php

namespace JOOservices\Client\Contracts;

use JOOservices\Client\Dto\UserAgentSpecification;
use JOOservices\Client\ValueObjects\UserAgent;

interface UserAgentGeneratorInterface
{
    public function generate(UserAgentSpecification $specification): UserAgent;
}
