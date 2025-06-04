<?php
/**
 * This file simply returns a PHP array mapping our short‐codes ('en','ru','jp')
 * → the full Faker locale identifiers ('en_US','ru_RU','ja_JP').
 * By putting them here, they’re no longer “hard‐coded” inline; you can extend
 * or override this file without touching generateBooks() directly.
 */
return [
    'en' => 'en_US',
    'ru' => 'ru_RU',
    'jp' => 'ja_JP',
];
