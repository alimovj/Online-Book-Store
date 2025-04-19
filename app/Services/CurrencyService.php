<?php

namespace App\Services;

use App\Models\Currency;

class CurrencyService
{
    public static function convert($amount, $from = 'USD', $to = 'UZS')
{
    $fromRate = Currency::where('code', $from)->value('rate') ?: 1;
    $toRate = Currency::where('code', $to)->value('rate') ?: 1;
    return ($amount / $fromRate) * $toRate;
}

}

