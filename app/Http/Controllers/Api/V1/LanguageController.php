<?php


namespace App\Http\Controllers\API\V1\Admin;

use App\Models\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\LanguageResource;
use App\Http\Requests\StoreLanguageRequest;
use App\Http\Requests\Admin\UpdateLanguageRequest;

class LanguageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:admin'])📥 Renamed image folde
    }

    public function index()
    {
        $languages = Language::latest()->paginate(10);
        return LanguageResource::collection($languages);
    }

    public function store(StoreLanguageRequest $request)
    {
        $language = Language::create($request->validated());

        return response()->json([
            'message' => __('Language created successfully.'),
            'data' => new LanguageResource($language)
        ]);
    }

    public function show($id)
    {
        $language = Language::findOrFail($id);
        return new LanguageResource($language);
    }

    public function update( UpdateLanguageRequest $request$updateLanguageRequest)
    {
        $language = Language::findOrFail($id);
        $language->update($request->validated());

        return response()->json([
            'message' => __('Language updated successfully.'),
            'data' => new LanguageResource($language)
        ]);
    }

    public function destroy($id)
    {
        $language = Language::findOrFail($id);
        $language->delete();

        return response()->json([
            'message' => __('Language deleted successfully.')
        ]);
    }
}
