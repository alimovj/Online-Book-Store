<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LinkTranslation extends Model
{
    protected $fillable = ['link_id', 'locale', 'name'];

    public $timestamps = true;
}
