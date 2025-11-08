<?php

namespace JOOservices\Client\ValueObjects;

use InvalidArgumentException;
use JOOservices\Client\Support\VersionComparator;

class VersionConstraint
{
    /** @var array<int, array{operator: string, version: string}> */
    private array $comparisons;

    private function __construct(array $comparisons)
    {
        $this->comparisons = $comparisons;
    }

    public static function fromString(string $expression): self
    {
        $expression = trim($expression);

        if ($expression === '') {
            throw new InvalidArgumentException('Version constraint expression cannot be empty.');
        }

        $parts = array_map('trim', explode(',', $expression));
        $comparisons = array_map(static function (string $part) {
            if ($part === '') {
                throw new InvalidArgumentException('Version constraint segment cannot be empty.');
            }

            if (preg_match('/^(>=|<=|>|<|==|=)\s*(.+)$/', $part, $matches) !== 1) {
                throw new InvalidArgumentException(sprintf('Unsupported version constraint segment "%s".', $part));
            }

            return [
                'operator' => $matches[1] === '==' ? '=' : $matches[1],
                'version' => trim($matches[2]),
            ];
        }, $parts);

        return new self($comparisons);
    }

    public function isSatisfiedBy(string $version): bool
    {
        foreach ($this->comparisons as $comparison) {
            if (! $this->checkComparison($version, $comparison['operator'], $comparison['version'])) {
                return false;
            }
        }

        return true;
    }

    private function checkComparison(string $candidate, string $operator, string $target): bool
    {
        $result = VersionComparator::compare($candidate, $target);

        return match ($operator) {
            '>' => $result === 1,
            '>=' => $result >= 0,
            '<' => $result === -1,
            '<=' => $result <= 0,
            '=' => $result === 0,
            default => throw new InvalidArgumentException(sprintf('Unsupported operator "%s".', $operator)),
        };
    }
}
