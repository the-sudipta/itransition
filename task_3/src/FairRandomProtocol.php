<?php
declare(strict_types=1);

class FairRandomProtocol {
    public function generate(int $range): array {
        $r    = SecureRandom::int(0, $range - 1);
        $key  = SecureRandom::bytes(32);
        $hmac = HmacGenerator::generate($key, (string)$r);
        return ['r' => $r, 'key' => $key, 'hmac' => $hmac];
    }

    public function reveal(array $g): void {
        $keyHex = bin2hex($g['key']);
        echo "Revealed key (hex): $keyHex\n";
        echo "Revealed number: {$g['r']}\n";
    }

    public function verify(array $g): bool {
        return hash_equals(
            $g['hmac'],
            HmacGenerator::generate($g['key'], (string)$g['r'])
        );
    }
}
