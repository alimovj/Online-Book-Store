<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Http\Requests\API\V1\Book\StoreBookRequest;
use App\Http\Requests\API\V1\Book\UpdateBookRequest;
use App\Http\Requests\Currency\ConvertCurrencyRequest;

class BookController extends Controller
{
    /**
     * Kitoblar ro‘yxati (filtrlash va paginatsiya bilan)
     */
    public function index(Request $request)
    {
        $books = Book::with(['categories', 'translations', 'images'])
            ->when($request->filled('category_id'), fn ($q) =>
                $q->whereHas('categories', fn ($q2) =>
                    $q2->where('id', $request->category_id)))
            ->when($request->filled('price_from'), fn ($q) =>
                $q->where('price', '>=', $request->price_from))
            ->when($request->filled('price_to'), fn ($q) =>
                $q->where('price', '<=', $request->price_to))
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('author', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            })
            ->latest()
            ->paginate(10);

        return BookResource::collection($books);
    }

    /**
     * Bitta kitobni ko‘rish (slug orqali)
     */
    public function show($slug)
    {
        $book = Book::with(['categories', 'translations', 'images'])
            ->where('slug', $slug)
            ->firstOrFail();

        return new BookResource($book);
    }

    /**
     * Yangi kitob qo‘shish
     */
    public function store(StoreBookRequest $request)
    {
        DB::beginTransaction();

        try {
            $book = Book::create([
                'author' => $request->author,
                'price' => $request->price,
            ]);

            // Tarjimalarni saqlash
            foreach ($request->translations as $lang => $data) {
                $book->translations()->create([
                    'locale' => $lang,
                    'title' => $data['title'],
                    'description' => $data['description'],
                ]);
            }

            // Kategoriyalarni ulash
            $book->categories()->sync($request->category_ids);

            // Rasmni saqlash
            if ($request->hasFile('image')) {
                $book->images()->create([
                    'url' => $request->file('image')->store('books', 'public'),
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => __('book.created_successfully'),
                'data' => new BookResource($book->load(['categories', 'translations', 'images']))
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mavjud kitobni yangilash
     */
    public function update(UpdateBookRequest $request, $slug)
    {
        $book = Book::where('slug', $slug)->firstOrFail();

        DB::beginTransaction();

        try {
            $book->update([
                'author' => $request->author,
                'price' => $request->price,
            ]);

            // Tarjimalarni yangilash
            foreach ($request->translations as $lang => $data) {
                $book->translations()->updateOrCreate(
                    ['locale' => $lang],
                    [
                        'title' => $data['title'],
                        'description' => $data['description'],
                    ]
                );
            }

            // Kategoriyalarni yangilash
            $book->categories()->sync($request->category_ids);

            // Yangi rasm bo‘lsa, eski rasmni o‘chirib, yangi rasmni saqlash
            if ($request->hasFile('image')) {
                $book->images()->delete();

                $book->images()->create([
                    'url' => $request->file('image')->store('books', 'public'),
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => __('book.updated_successfully'),
                'data' => new BookResource($book->load(['categories', 'translations', 'images']))
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Update failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Kitobni o‘chirish (slug orqali)
     */
    public function destroy($slug)
    {
        $book = Book::where('slug', $slug)->firstOrFail();

        $book->images()->delete();
        $book->translations()->delete();
        $book->categories()->detach();
        $book->delete();

        return response()->json([
            'status' => true,
            'message' => __('book.deleted_successfully'),
        ]);
    }

    public function convert(ConvertCurrencyRequest $request)
    {
        $book = Book::find($request->book_id);
        $convertedPrice = $book->getPriceIn($request->currency);

        if ($convertedPrice === null) {
            return response()->json([
                'status' => false,
                'message' => 'Currency rate not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Price converted',
            'data' => [
                'price' => $convertedPrice,
                'currency' => $request->currency
            ]
        ]);
    }
}
