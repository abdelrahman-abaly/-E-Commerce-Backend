<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PasswordResetController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function forgot(ForgotPasswordRequest $request): JsonResponse
    {
        $this->authService->forgotPassword($request->email);

        return response()->json([
            'message' => 'تم إرسال رابط إعادة تعيين كلمة المرور على بريدك الإلكتروني',
        ]);
    }

    public function reset(ResetPasswordRequest $request): JsonResponse
    {
        $this->authService->resetPassword($request->validated());

        return response()->json([
            'message' => 'تم إعادة تعيين كلمة المرور بنجاح، سجّل دخولك من جديد',
        ]);
    }
}
