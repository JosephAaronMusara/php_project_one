<?php

declare(strict_types=1);

function getTransactionFiles(string $dirPath): array
{
    $files = [];

    foreach (scandir($dirPath) as $file) {
        // Skip the special entries '.' and '..'
        if ($file === '.' || $file === '..') {
            continue;
        }

        // Use the full path to check if it's a directory
        $fullPath = $dirPath . $file;
        if (is_dir($fullPath)) {
            continue;
        }
        $files[] = $fullPath;
    }
    return $files;
}


function getTransactions(string $fileName): array
{

    if (!file_exists($fileName)) {
        trigger_error("File $fileName doesn't exist", E_USER_ERROR);
    }

    $file = fopen($fileName, 'r');
    
    fgetcsv($file);//to discard the first row

    $transactions = [];
    while (($transaction = fgetcsv($file)) !== false) {
        $transactions[] = $transaction;
    }

    return $transactions;
}