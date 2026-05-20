<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function __construct(private AuthService $authService){}

    public function __invoke(RegisterRequest $request):JsonResponse
    {
        $result= $this->authService->register($request->validated());

        return response()->json([
            'message'=>'تم إنشاء الحساب بنجاح، تحقق من بريدك الإلكتروني',
            'data'=>[
                'usesr'=>new UserResource($result['user']),
                'token'=>$result['token']
            ]
        ],201);
    }
}
