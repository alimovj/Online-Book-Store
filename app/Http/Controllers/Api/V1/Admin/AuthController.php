<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Auth\LoginRequest;
use App\Http\Requests\API\V1\Auth\RegisterRequest;
use App\Jobs\SendVerificationEmailJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Foydalanuvchini ro'yxatdan o'tkazish
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Email verification notification yuborish
        dispatch(new SendVerificationEmailJob($user));

        return response()->json([
            'status'  => true,
            'message' => __('auth.verification_sent'),
        ], 201);
    }

    /**
     * Foydalanuvchini tizimga kiritish
     */
    public function login(LoginRequest $request)
    {
        // Login qilinishiga harakat qilish
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status'  => false,
                'message' => __('auth.failed'),
            ], 401);
        }

        $user = Auth::user();

        // Email tasdiqlanganligini tekshirish
        if (!$user->hasVerifiedEmail()) {
            return response()->json([
                'status'  => false,
                'message' => __('auth.email_not_verified'),
            ], 403);
        }

        // Token yaratish
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => __('auth.login_success'),
            'token'   => $token,
            'user'    => $user,
        ]);
    }

    /**
     * Foydalanuvchini tizimdan chiqarish
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => true,
            'message' => __('auth.logout_success'),
        ]);
    }
}
