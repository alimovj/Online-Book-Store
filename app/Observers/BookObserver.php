<?php
namespace App\Observers;

use App\Models\Book;
use Illuminate\Support\Str;

class BookObserver
{
    public function creating(Book $book)
    {
        $book->slug = Str::slug($book->author . '-' . now()->timestamp);
    }

    public function updating(Book $book)
    {
        if ($book->isDirty('author')) {
            $book->slug = Str::slug($book->author . '-' . now()->timestamp);
        }
    }
}
