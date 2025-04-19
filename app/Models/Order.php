<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'book_id', 'status', 'quantity' , 'total_price' ];

    public function books()
    {
        return $this->belongsToMany(Book::class)->withPivot('quantity');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
