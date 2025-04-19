<?php 


namespace App\Http\Controllers\Api;

use App\Models\Currency;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Currency\StoreCurrencyRequest;
use App\Http\Requests\Currency\UpdateCurrencyRequest;
use App\Http\Requests\Currency\ConvertCurrencyRequest;

class CurrencyController extends Controller
{
    // Barcha valyutalarni olish
         public function index($code)
    {
        $currencies = Cache::remember('currencies', 60, function () {
            return Currency::all();
        });

        return response()->json([
            'success' => true,
            'data' => $currencies
        ]);
    }

    // Yangi valyuta qo‘shish
    public function store(StoreCurrencyRequest $request)
    {
        $validated = $request->validated(); // FormRequestda validatsiya ishlatiladi

        $currency = Currency::create($validated);
        Cache::forget('currencies');

        return response()->json([
            'message' => 'Currency created',
            'data' => $currency
        ], 201);
    }

    // Bitta valyutani olish
    public function show($code)
    {
        $currency = Currency::where('code', strtoupper($code))->firstOrFail();

        return response()->json([
            'data' => $currency
        ]);
    }

    // Valyutani yangilash
    public function update(UpdateCurrencyRequest $request, $code)
    {
        $currency = Currency::where('code', strtoupper($code))->firstOrFail();

        $validated = $request->validated(); // FormRequestda validatsiya ishlatiladi

        $currency->update($validated);
        Cache::forget('currencies');

        return response()->json([
            'message' => 'Currency updated',
            'data' => $currency
        ]);
    }

    // Valyutani o‘chirish
    public function destroy($code)
    {
        $currency = Currency::where('code', strtoupper($code))->firstOrFail();
        $currency->delete();
        Cache::forget('currencies');

        return response()->json([
            'message' => 'Currency deleted'
        ]);
    }

    // Konvertatsiya qilish (USD -> UZS)
    public function convert(ConvertCurrencyRequest $request) // Validatsiyani chaqiramiz
    {
        // FormRequestda validatsiya ishlatiladi
        $validated = $request->validated(); 

        $fromRate = Currency::where('code', strtoupper($validated['from']))->value('rate');
        $toRate = Currency::where('code', strtoupper($validated['to']))->value('rate');

        if (!$fromRate || !$toRate) {
            return response()->json(['message' => 'Invalid currency'], 400);
        }

        $converted = ($validated['amount'] / $fromRate) * $toRate;

        return response()->json([
            'amount' => round($converted, 2),
            'from' => strtoupper($validated['from']),
            'to' => strtoupper($validated['to']),
        ]);
    }
}
