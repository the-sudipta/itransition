<?php
declare(strict_types=1);

class CliMenu {
    public static function chooseDice(array $diceList, string $prompt): int {
        while (true) {
            echo $prompt, "\n";
            foreach ($diceList as $i => $d) {
                echo "  $i) $d\n";
            }
            echo "  X) Exit\n  ?) Show chances\nEnter your choice: ";
            $c = trim(fgets(STDIN));
            if ($c === '?') {
                HelpTableRenderer::render($diceList);
                continue;
            }
            if (strcasecmp($c, 'X') === 0) {
                exit("Goodbye!\n");
            }
            if (ctype_digit($c) && isset($diceList[(int)$c])) {
                return (int)$c;
            }
            echo "Invalid, try again.\n";
        }
    }

    public static function promptNumber(string $msg, int $min, int $max): int {
        while (true) {
            echo "$msg (between $min and $max): ";
            $l = trim(fgets(STDIN));
            if (ctype_digit($l) && ($v = (int)$l) >= $min && $v <= $max) {
                return $v;
            }
            echo "Please enter a number $min to $max.\n";
        }
    }
}
