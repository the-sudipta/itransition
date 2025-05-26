<?php
declare(strict_types=1);

// 1) Auto-load any class in src/ by its class‐name
spl_autoload_register(function(string $class){
    $file = __DIR__ . '/src/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// 2) Load the Console_Table library
require_once __DIR__ . '/Console/Table.php';

try {
    $parser   = new DiceParser();
    $diceList = $parser->parse($argv);
    (new GameController($diceList))->run();
} catch (Exception $e) {
    fwrite(STDERR, "Error: {$e->getMessage()}\n");
    exit(1);
}
