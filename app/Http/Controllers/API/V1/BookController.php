<?php
namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Book\StoreBookRequest;
use App\Http\Requests\API\V1\Book\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    /**
     * Kitoblar ro'yxati (pagination bilan)
     */
    public function index(Request $request)
    {
        $books = Book::with(['categories', 'translations', 'images'])
            ->latest()
            ->paginate(10);

        return BookResource::collection($books);
    }

    /**
     * Bitta kitobni ko‘rsatish
     */
    public function show($slug)
    {
        $book = Book::with(['categories', 'translations', 'images'])
            ->where('slug', $slug)
            ->firstOrFail();

        return new BookResource($book);
    }

    /**
     * Kitob yaratish
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

            // Categorylar
            $book->categories()->sync($request->category_ids);

            // Rasm yuklash
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
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Kitobni yangilash
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

            // Category
            $book->categories()->sync($request->category_ids);

            // Rasm yangilash (optional)
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
}
