<?php 
namespace App\Repositories;

use App\Models\Currency;

class CurrencyRepository
{
    public function all()
    {
        return Currency::all();
    }

    public function findByCode($code)
    {
        return Currency::where('code', strtoupper($code))->first();
    }

    public function createOrUpdate($code, $rate)
    {
        return Currency::updateOrCreate(['code' => $code], ['rate' => $rate]);
    }
}
