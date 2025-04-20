<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationTranslation extends Model
{
    protected $fillable = ['notification_id', 'locale', 'title', 'body'];

    public $timestamps = true;
}
