<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Models\Like;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Wishlistdagi kitoblar ro‘yxati
     */
    public function index(): 
    {
        $likes = Like::with('book.image')
            ->where('user_id', Auth::id())
            ->latest()
            ->get()
            ->pluck('book')
            ->filter(); // null qiymatlarni olib tashlaydi

        return response()->json([
            'message' => 'Your wishlist',
            'data' => BookResource::collection($likes),
        ]);
    }

    /**
     * Wishlistga yangi kitob qo‘shish
     */
    public function store($book_id): JsonResponse
    {
        $book = Book::findOrFail($book_id);

        $like = Like::firstOrCreate([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
        ]);

        return response()->json([
            'message' => 'Book added to wishlist',
            'data' => new BookResource($book),
        ], 201);
    }

    /**
     * Wishlistdan kitobni olib tashlash
     */
    public function destroy($book_id): JsonResponse
    {
        $deleted = Like::where('user_id', Auth::id())
            ->where('book_id', $book_id)
            ->delete();

        return response()->json([
            'message' => $deleted ? 'Book removed from wishlist' : 'Book not found in wishlist',
        ]);
    }

    /**
     * Wishlistga kitobni qo‘shish yoki olib tashlash (toggle)
     */
    public function toggle(Book $book): JsonResponse
    {
        $user = Auth::user();

        if ($user->likes()->where('book_id', $book->id)->exists()) {
            $user->likes()->detach($book->id);
            return response()->json([
                'message' => 'Removed from wishlist',
            ]);
        } else {
            $user->likes()->attach($book->id);
            return response()->json([
                'message' => 'Added to wishlist',
            ]);
        }
    }
}
