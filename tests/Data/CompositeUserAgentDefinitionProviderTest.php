<?php

namespace JOOservices\Client\Tests\Data;

use JOOservices\Client\Data\CompositeUserAgentDefinitionProvider;
use JOOservices\Client\Data\JsonUserAgentDefinitionProvider;
use JOOservices\Client\Data\UserAgentDefinitionProvider;
use PHPUnit\Framework\TestCase;

class CompositeUserAgentDefinitionProviderTest extends TestCase
{
    public function test_it_merges_definitions_from_multiple_sources(): void
    {
        $default = new UserAgentDefinitionProvider();
        $json = new JsonUserAgentDefinitionProvider(__DIR__.'/../Fixtures/user_agents.json');

        $provider = new CompositeUserAgentDefinitionProvider([$default, $json]);
        $definitions = $provider->definitions();

        self::assertGreaterThan(count($default->definitions()), $definitions);
    }
}
