<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerifyEmailController extends Controller
{
    public function __invoke(EmailVerificationRequest $request)
    {
        $request->fulfill(); // email_verified_at ni to‘ldiradi
        return response()->json(['message' => __('auth.email_verified')]);
    }
}
