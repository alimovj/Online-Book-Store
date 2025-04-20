<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $fillable = ['locale', 'key', 'value', 'is_active'];   

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

   
}
