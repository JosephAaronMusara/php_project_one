<?php
declare(strict_types= 1);

function formatDollarAmount(float $amount): string
{
    $is_negative = $amount < 0 ;

    return($is_negative?'-':'') . '$' .number_format(abs($amount),2);
}

function formarDate(string $date): string
{
    return date('M j, Y', strtotime($date));
}