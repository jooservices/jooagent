<?php

namespace JOOservices\Client\Tests\Data;

use InvalidArgumentException;
use JOOservices\Client\Data\JsonUserAgentDefinitionProvider;
use PHPUnit\Framework\TestCase;

class JsonUserAgentDefinitionProviderTest extends TestCase
{
    public function test_it_loads_definitions_from_json(): void
    {
        $provider = new JsonUserAgentDefinitionProvider(__DIR__.'/../Fixtures/user_agents.json');

        $definitions = $provider->definitions();

        self::assertCount(1, $definitions);
        self::assertSame('linux', $definitions[0]->operatingSystem()->slug());
        self::assertSame(['desktop', 'linux'], $definitions[0]->tags());
    }

    public function test_it_returns_empty_when_file_missing(): void
    {
        $provider = new JsonUserAgentDefinitionProvider(__DIR__.'/../Fixtures/missing.json');

        self::assertSame([], $provider->definitions());
    }

    public function test_it_throws_for_invalid_json(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $provider = new JsonUserAgentDefinitionProvider(__DIR__.'/../Fixtures/invalid.json');
        $provider->definitions();
    }
}
