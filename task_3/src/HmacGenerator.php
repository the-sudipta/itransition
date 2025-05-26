<?php
declare(strict_types=1);

class HmacGenerator {
    public static function generate(string $key, string $message): string {
        return hash_hmac('sha3-256', $message, $key);
    }
}
