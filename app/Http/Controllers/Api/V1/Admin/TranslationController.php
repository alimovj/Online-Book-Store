<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\App;
use App\Http\Requests\StoreTranslationRequest;
use App\Http\Requests\UpdateTranslationRequest;

class TranslationController extends Controller
{
   
    public function index(Request $request)
    {
       
        $locale = $request->header('locale'); // frontend dan uzatiladigan header: locale

        $translations = Translation::query()
            ->where('is_active', true)
            ->when($locale, function ($query, $locale) {
                $query->where('locale', $locale);
            })
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->key => $item->value];
            });

        return response()->json([
            'locale' => $locale ?? 'all',
            'translations' => $translations,
            'status' => true,
            'message' => __('messages.success'),
            'data' => $translations,
        

    ]);

    }

    /**
     * POST /api/v1/admin/translations
     * Yangi tarjima qo‘shish
     */
    public function store(StoreTranslationRequest $request)
    {
        $data = $request->validated();
        $translation = Translation::create($data);

        // keshni tozalash
        Cache::forget("translations_{$translation->locale}");

        return response()->json([
            'status' => true,
            'message' => __('messages.created'),
            'data' => $translation,
        ]);
    }

    /**
     * PUT /api/v1/admin/translations/{id}
     * Tarjimani yangilash
     */
    public function update(UpdateTranslationRequest $request, Translation $translation)
    {
        $data = $request->validated();
        $translation->update($data);

        Cache::forget("translations_{$translation->locale}");

        return response()->json([
            'status' => true,
            'message' => __('messages.updated'),
            'data' => $translation,
        ]);
    }

    /**
     * DELETE /api/v1/admin/translations/{id}
     * Tarjimani o‘chirish
     */
    public function destroy(Translation $translation)
    {
        $locale = $translation->locale;
        $translation->delete();

        Cache::forget("translations_{$locale}");

        return response()->json([
            'status' => true,
            'message' => __('messages.deleted'),
        ]);
    }

    /**
     * Barcha tarjimalarni olish (admin yoki texnik maqsadlar uchun)
     * GET /api/v1/admin/translations/all
     */
    public function all()
    {
        return Cache::remember('translations', 60, function () {
            return Translation::all();
        });
    }
    public function getByLocale($locale)
    {
        $translations = Translation::where('locale', $locale)->get();

        return response()->json([
            'status' => true,
            'data' => $translations
        ]);
    }
}
