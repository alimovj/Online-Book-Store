<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use League\CommonMark\Extension\CommonMark\Node\Inline\Image;

class Book extends Model
{
    protected $fillable = [
        'slug', 'author', 'price',
    ];

    
    public function translations()
    {
        return $this->hasMany(Translation::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
