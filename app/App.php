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


function getTransactions(string $fileName, ?callable $transactionHandler = null): array
{

    if (!file_exists($fileName)) {
        trigger_error("File $fileName doesn't exist", E_USER_ERROR);
    }

    $file = fopen($fileName, 'r');
    
    fgetcsv($file);//to discard the first row

    $transactions = [];
    while (($transaction = fgetcsv($file)) !== false) {
        if ($transactionHandler !== null) {
            $transaction = $transactionHandler($transaction);
        }
        $transactions[] = $transaction;
    }

    return $transactions;
}

function extractTransaction(array $transactionRow): array
{
    [$date,$checkNumber,$transaction,$amount] = $transactionRow;

    $amount =(float)str_replace(['$',','],'', $amount);


    return [
        'date'=> $date,
        'checkNumber'=> $checkNumber,
        'transaction'=> $transaction,
        'amount'=> $amount,
    ];
}

function calculateTotals(array $transactions): array
{
    $totals = ['netTotal'=>0, 'totalIncome'=>0,'totalExpenses'=>0];

    foreach ($transactions as $transaction) {
        $totals['netTotal'] += $transaction['amount'];
        if($transaction['amount']>=0){

            $totals['totalIncome'] += $transaction['amount'];
        }else{
            $totals['totalExpenses'] += $transaction['amount'];
        }
    }
    return $totals;
}
