<?php
declare(strict_types=1);

class GameController {
    private array $diceList;
    private FairRandomProtocol $protocol;

    public function __construct(array $diceList) {
        $this->diceList = $diceList;
        $this->protocol = new FairRandomProtocol();
    }

    public function run(): void {
        // Step 1: pick who goes first
        echo "\nWelcome!\nStep 1: Who picks dice first?\n";
        $f     = $this->protocol->generate(2);
        echo "Computer locked its choice: HMAC={$f['hmac']}\n";
        $guess = CliMenu::promptNumber('Your guess (0 or 1)', 0, 1);
        echo "You guessed $guess\n";

        // reveal & verify
        $this->protocol->reveal($f);
        if (!$this->protocol->verify($f)) {
            exit("Check failed\n");
        }

        $userFirst = ($guess === $f['r']);
        echo "\nStep 2: Choose dice\n";

        // picking dice
        if ($userFirst) {
            $userIndex = CliMenu::chooseDice($this->diceList, 'Your turn: pick a die');
            echo "You picked die $userIndex: {$this->diceList[$userIndex]}\n";
            $remaining = $this->diceList;
            unset($remaining[$userIndex]);
            $compIndex = array_keys($remaining)[ SecureRandom::int(0, count($remaining) -1) ];
            echo "Computer picks die $compIndex: {$this->diceList[$compIndex]}\n";
        } else {
            echo "Computer picks first...\n";
            $allKeys   = array_keys($this->diceList);
            $compIndex = $allKeys[ SecureRandom::int(0, count($allKeys) -1) ];
            echo "Computer picked die $compIndex: {$this->diceList[$compIndex]}\n";
            $remaining = $this->diceList;
            unset($remaining[$compIndex]);
            $userIndex = CliMenu::chooseDice($remaining, 'Your turn: pick a die');
            echo "You picked die $userIndex: {$this->diceList[$userIndex]}\n";
        }

        // rolls & result
        echo "\nStep 3: Your roll\n";
        $userRoll = $this->roll($this->diceList[$userIndex]);

        echo "\nStep 4: Computer's roll\n";
        $compRoll = $this->roll($this->diceList[$compIndex]);

        echo "\nStep 5: Compare\n";
        echo "You got $userRoll, computer got $compRoll.\n";
        echo $userRoll > $compRoll
            ? "You win!\n"
            : ($userRoll < $compRoll ? "Computer wins!\n" : "It's a tie!\n");
    }

    private function roll(Dice $d): int {
        $round = $this->protocol->generate($d->faceCount());
        echo "Computer locked secret: HMAC={$round['hmac']}\n";
        $y       = CliMenu::promptNumber('Enter your secret for this roll', 0, $d->faceCount() -1);
        echo "You chose $y\n";

        $this->protocol->reveal($round);
        if (!$this->protocol->verify($round)) {
            exit("Check failed\n");
        }

        $idx   = ($round['r'] + $y) % $d->faceCount();
        $value = $d->getValue($idx);
        echo "Result slot $idx, face $value\n";
        return $value;
    }
}
