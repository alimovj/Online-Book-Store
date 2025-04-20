<?php 
namespace App\Models;

use Nette\Utils\Image;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = ['title', 'slug', 'description', 'author', 'price'];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
    ];

    // 📘 Translations
    public function translations()
    {
        return $this->hasMany(BookTranslation::class);
    }
    
    public function translation($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations->where('locale', $locale)->first();
    }
    
    // 📸 Images
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    // 🏷️ Categories
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    // ❤️ Likes
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // 👤 Author
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    
    public function isLikedBy(User $user): bool {
        return $this->likes()->where('user_id', $user->id)->exists();
    }
    protected static function booted()
    {
        static::creating(function ($book) {
            $book->slug = Str::slug($book->title['uz']); // default til
        });
    }
    public function getPriceIn($currency)
    {
        if ($currency === 'UZS') return $this->price;
    
        $rate = ExchangeRate::where('from_currency', 'UZS')
                    ->where('to_currency', $currency)
                    ->latest()
                    ->first();
    
        return $rate ? round($this->price / $rate->rate, 2) : null;
    }

    


}
