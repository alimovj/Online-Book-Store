<?php

namespace App\Observers;

use App\Models\Category;

class CategoryObserver
{

    public function saved($model)
{
    cache()->forget('active_languages');
    cache()->forget('active_translations');
}

public function deleted($model)
{
    cache()->forget('active_languages');
    cache()->forget('active_translations');
}
}
