<?php

namespace JOOservices\Client\Data;

use InvalidArgumentException;
use JOOservices\Client\Contracts\UserAgentDefinitionProviderInterface;
use JOOservices\Client\Dto\UserAgentDefinition;

class CompositeUserAgentDefinitionProvider implements UserAgentDefinitionProviderInterface
{
    /**
     * @param UserAgentDefinitionProviderInterface[] $providers
     */
    public function __construct(private array $providers)
    {
        foreach ($this->providers as $provider) {
            if (! $provider instanceof UserAgentDefinitionProviderInterface) {
                throw new InvalidArgumentException('Composite provider expects only UserAgentDefinitionProviderInterface instances.');
            }
        }
    }

    public function definitions(): array
    {
        $definitions = [];

        foreach ($this->providers as $provider) {
            $definitions = [...$definitions, ...$provider->definitions()];
        }

        return $definitions;
    }
}
