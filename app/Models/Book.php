<?php 
namespace App\Models;

use Nette\Utils\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = ['title', 'slug', 'content', 'user_id'];

    // 📘 Translations
    public function translations(): HasMany
    {
        return $this->hasMany(Translation::class);
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
    
}
