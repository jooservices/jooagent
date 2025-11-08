<?php

namespace JOOservices\Client\ValueObjects;

class UserAgentPattern
{
    public function __construct(private string $pattern)
    {
    }

    public function pattern(): string
    {
        return $this->pattern;
    }

    /**
     * @param array<string, string> $variables
     */
    public function render(array $variables): string
    {
        $rendered = $this->pattern;

        foreach ($variables as $key => $value) {
            $rendered = str_replace('{'.$key.'}', $value, $rendered);
        }

        return $rendered;
    }
}
