<?php

namespace JOOservices\Client\Contracts;

interface HttpClientInterface
{
    public function get(string $url, array $headers = []): string;
}
