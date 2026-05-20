<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    // إرسال الـ verification email من جديد
    public function send(Request $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'البريد الإلكتروني مفعّل بالفعل',
            ]);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'تم إرسال رابط التفعيل على بريدك الإلكتروني',
        ]);
    }

    // التحقق من الـ email بعد الضغط على الرابط
    public function verify(EmailVerificationRequest $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'البريد الإلكتروني مفعّل بالفعل',
            ]);
        }

        $request->fulfill(); // بيعمل mark كـ verified ويطلق الـ event

        event(new Verified($request->user()));

        return response()->json([
            'message' => 'تم تفعيل البريد الإلكتروني بنجاح',
        ]);
    }
}
