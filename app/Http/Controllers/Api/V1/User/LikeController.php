<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\BookResource;

class LikeController extends Controller
{
   
    public function toggle(Request $request, Book $book)
    {
        $user = Auth::user();

        $like = $book->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();

            return response()->json([
                'status' => true,
                'message' => 'Kitob wishlistdan olib tashlandi.'
            ]);
        }

        $book->likes()->create([
            'user_id' => $user->id
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Kitob wishlistga qo‘shildi.'
        ]);
    }

    /**
     * Foydalanuvchi like bosgan barcha kitoblar ro‘yxati
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $likedBooks = Book::whereHas('likes', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->paginate(10);

        return BookResource::collection($likedBooks)->additional([
            'status' => true,
            'message' => 'Wishlistdagi kitoblar ro‘yxati'
        ]);
    }
}
