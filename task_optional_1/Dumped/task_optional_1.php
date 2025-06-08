<?php
// SELF-TRANSFORMING FILE: PHP <--> PYTHON

$filename = basename(__FILE__, '.php');

// Python code stored as HEREDOC string
$py_code = <<<PY
import os

filename = os.path.splitext(__file__)[0]

php_code = """{ESCAPED_PHP}"""

with open(f"{filename}.php", "w", encoding="utf-8") as f:
    f.write(php_code)

os.remove(__file__)
print(f"Converted to PHP: {filename}.php")
PY;

// Escape and replace placeholder
$escaped_py = str_replace("{ESCAPED_PHP}", addslashes(file_get_contents(__FILE__)), $py_code);

// Write the .py file and delete self
file_put_contents("$filename.py", $escaped_py);
unlink(__FILE__);
echo "Converted to Python: $filename.py\n";
?>
