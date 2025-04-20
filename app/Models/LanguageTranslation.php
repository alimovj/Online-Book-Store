<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class LanguageTranslation extends Model
{
    protected $fillable = ['language_id', 'locale', 'name'];

    public $timestamps = true;
}
