<?php

namespace JOOservices\Client\Contracts;

interface BrowserVersionSourceInterface
{
    public function browser(): string;

    public function fetchLatestVersion(): string;
}
