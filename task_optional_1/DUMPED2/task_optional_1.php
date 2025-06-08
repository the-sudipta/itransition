<?php

$tpl = <<<'NOW'
<?php

$tpl = %s;

$py = sprintf($tpl, var_export($tpl, true));

echo 'print(' . var_export($py, true) . ')';
?>
NOW;

$py = sprintf($tpl, var_export($tpl, true));

// 3) Output the final Python program:
echo 'print(' . var_export($py, true) . ')';
