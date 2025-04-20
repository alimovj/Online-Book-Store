<?php

namespace App\Models;

use Wishlist;
use Illuminate\Database\Eloquent\Model;

class WishlistTranslation extends Model
{
    protected $fillable = ['wishlist_id', 'language', 'message'];

    public function wishlist()
    {
        return $this->belongsTo(Wishlist::class);
    }
}
