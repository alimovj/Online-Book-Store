<?php 
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Language;

class LangController extends Controller
{
      public function index():
    {
        return response()->json(Language::where('is_active', true)->get());
    }
}
