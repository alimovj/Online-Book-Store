<?php

namespace App\Services;

use App\Models\Book;
use App\Models\BookTranslation;
use Illuminate\Support\Facades\DB;

class BookService
{
    public function create(array $data)
    {

        try {
            // Kitobni saqlash
            $book = Book::create([
                'author_id' => $data['author_id'],
                'content' => $data['content'],
            ]);

        
            $locales = config('translatable.locales');
            
            foreach ($locales as $locale) {
                BookTranslation::create([
                    'book_id' => $book->id,
                    'locale' => $locale,
                    'title' => $data['title'][$locale] ?? '',
                    'description' => $data['description'][$locale] ?? '',
                ]);
            }

            // 3. Barcha o'zgarishlarni saqlash
            DB::commit();
        } catch (\Exception $e) {
            // Xatolik yuzaga kelsa, barcha o'zgarishlarni qaytarib olish
            DB::rollBack();
            throw $e;
        }
    }
}
