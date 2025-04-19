<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BookFilter

{
    public function scopeFilter($query, Request $request)
    {
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
    
        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }
    }
    


}