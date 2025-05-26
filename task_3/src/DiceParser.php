<?php
declare(strict_types=1);

class DiceParser {
    public function parse(array $args): array {
        array_shift($args);
        if (count($args) < 3) {
            fwrite(STDERR, "ERROR: You gave " . count($args) . " dice. Need at least 3.\n");
            exit(1);
        }
        $list = [];
        foreach ($args as $spec) {
            $parts = explode(',', $spec);
            if (count($parts) !== 6) {
                fwrite(STDERR, "ERROR: \"$spec\" is invalid. Use 6 numbers like 2,2,4,4,9,9.\n");
                exit(1);
            }
            $list[] = new Dice(array_map('intval', $parts));
        }
        return $list;
    }
}
