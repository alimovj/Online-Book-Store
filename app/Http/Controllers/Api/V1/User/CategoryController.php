<?php
namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;

class CategoryController extends Controller
{
    
               public function index(  $slug)
    {
        $categories = Category::with(['translations', 'children'])
            ->whereNull('parent_id')
            ->paginate(10);

        return CategoryResource::collection($categories);
    }

   
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        $slug = Str::slug($data['translations']['en']['title'] ?? now()->timestamp);
        $category = Category::create([
            'slug' => $slug,
            'parent_id' => $request->input('parent_id')
        ]);

        foreach ($data['translations'] as $locale => $trans) {
            $category->translations()->create([
                'locale' => $locale,
                'title' => $trans['title'],
                'description' => $trans['description'] ?? null,
            ]);
        }

        return new CategoryResource($category->load('translations'));
    }

    
    public function show($slug)
    {
        $category = Category::with(['translations', 'children.translations', 'books.translations'])
            ->where('slug', $slug)
            ->firstOrFail();

        return new CategoryResource($category);
    }

        public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = $request->validated();

        foreach ($data['translations'] as $locale => $trans) {
            $category->translations()->updateOrCreate(
                ['locale' => $locale],
                ['title' => $trans['title'], 'description' => $trans['description'] ?? null]
            );
        }

        return new CategoryResource($category->load('translations'));
    }

    
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json([
            'message' => __('Category deleted successfully')
        ]);
    }
}
