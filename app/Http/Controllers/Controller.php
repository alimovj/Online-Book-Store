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
public function success($data = [], $message = 'Success') {
    return response()->json([
        'success' => true,
        'message' => $message,
        'data' => $data
    ]);
}

public function error($message = 'Error', $code = 400) {
    return response()->json([
        'success' => false,
        'message' => $message
    ], $code);
}

}
