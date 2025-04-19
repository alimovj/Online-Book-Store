<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

abstract class Controller
{
    public function index(Request $request)
{
    return Book::filter($request)->paginate(10);
}

}
