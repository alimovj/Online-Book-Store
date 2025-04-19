<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Currency;

class CurrencyRateSyncCommand extends Command
{
    protected $signature = 'currency:sync';
    protected $description = 'Valyuta kurslarini tashqi APIdan yuklab olish';

    public function handle()
    {
        $this->info("Valyuta kurslari yuklanmoqda...");

        try {
            $response = Http::get('https://api.exchangerate-api.com/v4/latest/USD'); // yoki boshqa API
            $data = $response->json();

            foreach ($data['rates'] as $code => $rate) {
                Currency::updateOrCreate(
                    ['code' => $code],
                    ['rate' => $rate]
                );
            }

            Cache::forget('currencies');
            $this->info("Valyuta kurslari muvaffaqiyatli yangilandi.");
        } catch (\Exception $e) {
            $this->error("Xatolik yuz berdi: " . $e->getMessage());
        }
    }
}
