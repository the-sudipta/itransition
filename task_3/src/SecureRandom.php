<?php
declare(strict_types=1);

class SecureRandom {
    public static function bytes(int $length): string {
        return random_bytes($length);
    }
    public static function int(int $min, int $max): int {
        return random_int($min, $max);
    }
}
