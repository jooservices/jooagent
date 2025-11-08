<?php

namespace JOOservices\Client\ValueObjects;

class Device
{
    public function __construct(
        private string $slug,
        private string $name,
        private string $descriptor,
        private string $type = 'generic'
    ) {
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function descriptor(): string
    {
        return $this->descriptor;
    }

    public function type(): string
    {
        return $this->type;
    }
}
