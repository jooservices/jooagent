<?php

namespace JOOservices\Client\Repositories;

use JOOservices\Client\Contracts\UserAgentRepositoryInterface;
use JOOservices\Client\Dto\UserAgentDefinition;
use JOOservices\Client\Dto\UserAgentSpecification;

class InMemoryUserAgentRepository implements UserAgentRepositoryInterface
{
    /**
     * @param UserAgentDefinition[] $definitions
     */
    public function __construct(private array $definitions)
    {
    }

    public function matching(UserAgentSpecification $specification): array
    {
        return array_values(array_filter(
            $this->definitions,
            static function (UserAgentDefinition $definition) use ($specification): bool {
                if ($specification->operatingSystem() !== null && $definition->operatingSystem()->slug() !== $specification->operatingSystem()) {
                    return false;
                }

                if ($specification->device() !== null && $definition->device()->slug() !== $specification->device()) {
                    return false;
                }

                if ($specification->browser() !== null && $definition->browser()->slug() !== $specification->browser()) {
                    return false;
                }

                if ($specification->tags() !== [] && array_diff($specification->tags(), $definition->tags()) !== []) {
                    return false;
                }

                return true;
            }
        ));
    }
}
