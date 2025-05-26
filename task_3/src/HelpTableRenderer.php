<?php
declare(strict_types=1);

class HelpTableRenderer {
    public static function render(array $diceList): void {
        $table   = new Console_Table();
        $headers = ['Dice'];
        foreach ($diceList as $d) {
            $headers[] = (string)$d;
        }
        $table->setHeaders($headers);

        foreach ($diceList as $i => $di) {
            $row = [(string)$di];
            foreach ($diceList as $j => $dj) {
                $row[] = $i === $j
                    ? '-'
                    : number_format(ProbabilityCalculator::winChance($di, $dj), 4);
            }
            $table->addRow($row);
        }

        echo "\n--- WIN PROBABILITIES ---\n";
        echo $table->getTable(), "\n";
    }
}
