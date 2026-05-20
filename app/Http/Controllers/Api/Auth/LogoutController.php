<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function __construct(private AuthService $authService){}

    public function logout(Request $request):JsonResponse{
        $this->authService->logout($request->user());
        return response()->json([
            'message'=>'تم تسجيل الخروج بنجاح'
        ]);
    }
    public function logoutAll(Request $request):JsonResponse{
        $this->authService->logoutAll($request->user());
        return response()->json([
            'message'=>'تم تسجيل الخروج من جميع الاجهزة'
        ]);
    }
}
