<?php

/**
 * TASK 2 — SHA3-256 Hash Submission
 *
 * Steps:
 * 1. Unzip the zipped file and get the 256 .data files
 * 2. Take an array of 256 size.
 * 3. Read each file as binary and then compute its SHA3-256 hash.
 *    Convert that hash to a 64-character lowercase hex (normal hash string).
 *    Store it inside the array.
 * 4. Take a variable. Then sort the 256 hashes from biggest to smallest (descending)
 *    and then join them (do not put any commas, spaces, or line breaks) into that variable.
 * 5. Then at the end of that string, attach your email: sudiptakumar400@gmail.com
 * 6. Now hash the full result again using SHA3-256.
 * 7. This should give the final result — a 64-character lowercase hex string.
 *    You will submit this as your solution.
 */

$folder = __DIR__ . '/binary'; // Path to your 256 binary files
$email = 'sudiptakumar400@gmail.com'; // Lowercase email
$hashes = [];

// Step 1–3: Read each file, compute SHA3-256, convert to lowercase hex
foreach (scandir($folder) as $file) {
    if (is_file("$folder/$file")) {
        $content = file_get_contents("$folder/$file");
        $hash = hash('sha3-256', $content); // Already in lowercase
        $hashes[] = $hash;
    }
}

// Step 4: Sort hashes as strings in descending order
rsort($hashes, SORT_STRING);

// Step 5: Join all hashes without separator
$joined = implode('', $hashes);

// Step 6: Append email
$final_string = $joined . strtolower($email);

// Step 7: Compute final SHA3-256 hash
$final_hash = hash('sha3-256', $final_string);


echo 'Total files: ' . count(array_filter(scandir('binary'), fn($f) => is_file("binary/$f"))) . PHP_EOL;

// Output final result
echo $final_hash . PHP_EOL;