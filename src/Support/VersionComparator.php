<?php

namespace JOOservices\Client\Support;

class VersionComparator
{
    public static function compare(string $left, string $right): int
    {
        $leftParts = self::tokenize($left);
        $rightParts = self::tokenize($right);

        $max = max(count($leftParts), count($rightParts));

        for ($i = 0; $i < $max; $i++) {
            $leftPart = $leftParts[$i] ?? 0;
            $rightPart = $rightParts[$i] ?? 0;

            $result = self::comparePart($leftPart, $rightPart);

            if ($result !== 0) {
                return $result;
            }
        }

        return 0;
    }

    /**
     * @return array<int, int|string>
     */
    private static function tokenize(string $version): array
    {
        $normalized = str_replace(['_', '-', ' '], '.', $version);
        $parts = array_filter(explode('.', $normalized), static fn ($part) => $part !== '');

        return array_map(static function (string $part) {
            return ctype_digit($part) ? (int) $part : $part;
        }, $parts);
    }

    private static function comparePart(int|string $left, int|string $right): int
    {
        if (is_int($left) && is_int($right)) {
            return $left <=> $right;
        }

        return (string) $left <=> (string) $right;
    }
}
