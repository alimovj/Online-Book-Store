<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'body',
        'is_read',
        'notifiable_id', 'notifiable_type', 'data',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
