<?php
// db_helpers.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/model/db_connect.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/itransition/utility_functions.php'; // show_error_page()

/**
 * Drops FKs, truncates all tables in the correct order, then re-creates FKs.
 * Wraps everything in a transaction so either all steps succeed or none do.
 */
function clearAllData()
{
    $conn = db_conn();
    try {
        // 1) Start transaction
        $conn->begin_transaction();

        // 2) Drop child→parent foreign keys
        $queries = [
            // logs → users
            "ALTER TABLE `logs` DROP FOREIGN KEY `logs_ibfk_1`",
            // user_details → users
            "ALTER TABLE `user_details` DROP FOREIGN KEY `user_details_ibfk_1`",
        ];
        foreach ($queries as $sql) {
            if (! $conn->query($sql)) {
                throw new Exception("Failed to drop FK: " . $conn->error);
            }
        }

        // 3) Truncate tables in child-to-parent order
        $tables = ['user_details', 'logs', 'users'];
        foreach ($tables as $tbl) {
            if (! $conn->query("TRUNCATE TABLE `{$tbl}`")) {
                throw new Exception("Failed to truncate {$tbl}: " . $conn->error);
            }
        }

        // 4) Re-create the foreign keys
        $queries = [
            // logs_ibfk_1
            <<<SQL
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE
SQL
            ,
            // user_details_ibfk_1
            <<<SQL
ALTER TABLE `user_details`
  ADD CONSTRAINT `user_details_ibfk_1`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE
SQL
        ];
        foreach ($queries as $sql) {
            if (! $conn->query($sql)) {
                throw new Exception("Failed to recreate FK: " . $conn->error);
            }
        }

        // 5) Commit if all went well
        $conn->commit();

    } catch (Exception $e) {
        // Roll back on error
        $conn->rollback();
        show_error_page(
            'Data Reset Error',
            $e->getMessage(),
            'database_error'
        );
    }
}

clearAllData();