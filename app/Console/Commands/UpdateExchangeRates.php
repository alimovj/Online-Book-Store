<?php  
 
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ExchangeRate;

class UpdateExchangeRates extends Command
{
    protected $signature = 'exchange:update';
    protected $description = 'Update exchange rates daily';

    public function handle()
    {
        // Bu yerda API chaqiriladi yoki fake data (demo)
        $rates = [
            ['from_currency' => 'UZS', 'to_currency' => 'USD', 'rate' => 12500],
            ['from_currency' => 'UZS', 'to_currency' => 'RUB', 'rate' => 140],
        ];

        foreach ($rates as $rate) {
            ExchangeRate::create($rate);
        }

        $this->info("Exchange rates updated!");
    }
}
