<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $fillable = ['language_id', 'book_id', 'title', 'description' , 'locale', 'key', 'value'];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
