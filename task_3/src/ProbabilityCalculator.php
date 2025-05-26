<?php
declare(strict_types=1);

class ProbabilityCalculator {
    public static function winChance(Dice $a, Dice $b): float {
        $wins = 0;
        $total = 0;
        for ($i = 0; $i < $a->faceCount(); $i++) {
            for ($j = 0; $j < $b->faceCount(); $j++) {
                $total++;
                if ($a->getValue($i) > $b->getValue($j)) {
                    $wins++;
                }
            }
        }
        return $total ? $wins / $total : 0.0;
    }
}
