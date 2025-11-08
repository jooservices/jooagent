<?php

namespace JOOservices\Client\ValueObjects;

class UserAgent
{
    public function __construct(private string $value)
    {
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
