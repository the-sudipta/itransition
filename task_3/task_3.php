<?php

declare(strict_types=1);

require_once __DIR__ . '/Console/Table.php';

class SecureRandom{
    public static function bytes(int $length): string { return random_bytes($length); }
    public static function int(int $min, int $max): int { return random_int($min, $max); }
}

class HmacGenerator{
    public static function generate(string $key, string $message): string {
        return hash_hmac('sha3-256', $message, $key);
    }
}

class Dice{
    private array $faces;
    public function __construct(array $faces) {
        if (count($faces) !== 6) throw new InvalidArgumentException("Each die needs 6 numbers.");
        $this->faces = $faces;
    }
    public function faceCount(): int { return count($this->faces); }
    public function getValue(int $i): int { return $this->faces[$i]; }
    public function __toString(): string { return implode(',', $this->faces); }
}

class DiceParser{
    public function parse(array $args): array{
        array_shift($args);
        if (count($args) < 3) {
            fwrite(STDERR, "ERROR: You gave " . count($args) . " dice. Need at least 3.\n");
            exit(1);
        }
        $list = [];
        foreach ($args as $spec) {
            $parts = explode(',', $spec);
            if (count($parts) !== 6) {
                fwrite(STDERR, "ERROR: \"$spec\" is invalid. Use 6 numbers like 2,2,4,4,9,9.\n"); exit(1);
            }
            $faces = array_map('intval', $parts);
            $list[] = new Dice($faces);
        }
        return $list;
    }
}

class ProbabilityCalculator{
    public static function winChance(Dice $a, Dice $b): float{
        $wins = $total = 0;
        for ($i=0;$i<$a->faceCount();$i++) for ($j=0;$j<$b->faceCount();$j++) {
            $total++;
            if ($a->getValue($i) > $b->getValue($j)) $wins++;
        }
        return $total ? $wins/$total : 0.0;
    }
}

class HelpTableRenderer{
    public static function render(array $diceList){
        $table = new Console_Table();
        $he = ['Dice']; foreach ($diceList as $d) $he[] = (string)$d;
        $table->setHeaders($he);
        foreach ($diceList as $i=>$di) {
            $row = [(string)$di];
            foreach ($diceList as $j=>$dj) {
                $row[] = $i===$j? '-' : number_format(ProbabilityCalculator::winChance($di,$dj),4);
            }
            $table->addRow($row);
        }
        echo "\n--- WIN PROBABILITIES ---\n";
        echo $table->getTable();
        echo "\n";
    }
}

class CliMenu{
    public static function chooseDice(array $diceList, string $text): int{
        while (true) {
            echo "$text\n";
            foreach ($diceList as $i=>$d) echo "  $i) $d\n";
            echo "  X) Exit\n";
            echo "  ?) Show chances\n";
            echo "Enter your choice: ";
            $c = trim(fgets(STDIN));
            if ($c==='?') { HelpTableRenderer::render($diceList); continue; }
            if (strcasecmp($c,'X')===0) exit("Goodbye!\n");
            if (ctype_digit($c) && isset($diceList[(int)$c])) return (int)$c;
            echo "Invalid, try again.\n";
        }
    }
    public static function promptNumber(string $msg,int $min,int $max): int{
        while (true) {
            echo "$msg (between $min and $max): ";
            $l = trim(fgets(STDIN));
            if (ctype_digit($l) && ($v=(int)$l)>=$min && $v<=$max) return $v;
            echo "Please enter a number $min to $max.\n";
        }
    }
}

class FairRandomProtocol{
    public function generate(int $range): array{
        $r = SecureRandom::int(0,$range-1);
        $key = SecureRandom::bytes(32);
        return ['r'=>$r,'key'=>$key,'hmac'=>HmacGenerator::generate($key,(string)$r)];
    }
    public function reveal(array $g){
        /** encode the raw bytes into hex so it prints cleanly */
        $keyHex = bin2hex($g['key']);
        echo "Revealed key (hex): $keyHex\n";
        echo "Revealed number: {$g['r']}\n";
    }
    public function verify(array $g): bool{
        return hash_equals($g['hmac'],HmacGenerator::generate($g['key'],(string)$g['r']));
    }
}

class GameController{
    private $diceList,$p;
    public function __construct($d) { $this->diceList=$d; $this->p=new FairRandomProtocol(); }
    public function run(){
        echo "\nWelcome!\nStep 1: Who picks dice first?\n";
        $f = $this->p->generate(2);
        echo "Computer locked its choice: HMAC={$f['hmac']}\n";
        $g=CliMenu::promptNumber('Your guess (0 or 1)',0,1);
        echo "You guessed $g\n";
        $this->p->reveal($f);
        if(!$this->p->verify($f)) exit("Check failed\n");
        $uf=($g===$f['r']);

        echo "\nStep 2: Choose dice\n";
        if($uf){
            $u=CliMenu::chooseDice($this->diceList,'Your turn: pick a die');
            echo "You picked die $u: {$this->diceList[$u]}\n";
            $rem=$this->diceList; unset($rem[$u]);
            $keys=array_keys($rem);
            $c=$keys[SecureRandom::int(0,count($keys)-1)];
            echo "Computer picks die $c: {$this->diceList[$c]}\n";
        }else{
            echo "Computer picks first...\n";
            $all=array_keys($this->diceList);
            $c=$all[SecureRandom::int(0,count($all)-1)];
            echo "Computer picked die $c: {$this->diceList[$c]}\n";
            $rem=$this->diceList; unset($rem[$c]);
            $u=CliMenu::chooseDice($rem,'Your turn: pick a die');
            echo "You picked die $u: {$this->diceList[$u]}\n";
        }
        $ud=$this->diceList[$u]; $cd=$this->diceList[$c];

        echo "\nStep 3: Your roll\n";
        $ur=$this->roll($ud);
        echo "\nStep 4: Computer's roll\n";
        $cr=$this->roll($cd);

        echo "\nStep 5: Compare\n";
        echo "You got $ur, computer got $cr.\n";
        echo $ur>$cr?"You win!\n":($ur<$cr?"Computer wins!\n":"It's a tie!\n");
    }
    private function roll(Dice $d){
        $r=$this->p->generate($d->faceCount());
        echo "Computer locked secret: HMAC={$r['hmac']}\n";
        $y=CliMenu::promptNumber('Enter your secret for this roll',0,$d->faceCount()-1);
        echo "You chose $y\n";
        $this->p->reveal($r);
        if(!$this->p->verify($r)) exit("Check failed\n");
        $idx=($r['r']+$y)%$d->faceCount();
        $val=$d->getValue($idx);
        echo "Result slot $idx, face $val\n";
        return $val;
    }
}
// Main
try{
    $dp=new DiceParser();
    $dl=$dp->parse($argv);
    (new GameController($dl))->run();
}catch(Exception $e){
    fwrite(STDERR,"Error: {$e->getMessage()}\n"); exit(1);
}
