<?php

namespace App\Support;

/**
 * Shared divide-by-zero-safe rate helper — returns `null` (never
 * `Infinity`/NAN) whenever the denominator is missing or zero. Mirrors
 * CricketAnalysisService::safeDivide (kept separate there since that class
 * is left untouched), reused by GenericSportAnalysisService and any other
 * analysis service that needs a rate computed from summed counts.
 */
class StatMath
{
    public static function safeDivide(int|float $numerator, int|float $denominator, int $decimals = 2): ?float
    {
        if ($denominator <= 0) {
            return null;
        }

        return round($numerator / $denominator, $decimals);
    }
}
