<?php 

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    require __DIR__.'/api/admin.php';
    require __DIR__.'/api/user.php';
});
