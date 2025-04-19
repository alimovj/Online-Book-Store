<?php 
namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['slug', 'title', 'parent_id'];

    protected $casts = [
        'title' => 'array', // JSONni array sifatida ishlatish
    ];

    public function books()
    {
        return $this->belongsToMany(Book::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // 🔁 Childlar
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // 🔁 Slug avtomatik
    protected static function booted()
    {
        static::creating(function ($category) {
            $category->slug = Str::slug($category->title['uz']); // yoki default til
        });
    }
}


