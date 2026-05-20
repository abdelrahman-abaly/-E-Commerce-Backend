<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class AuthService
{

    // ==================== Register ====================

    public function register(array $data): array
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $data['password'], // بيتعمله hash تلقائياً في الـ cast
            'phone'    => $data['phone'] ?? null,
            'role'     => UserRole::CUSTOMER,
        ]);

        // Assign customer role
        $user->assignRole(UserRole::CUSTOMER->value);

        // Fire registered event — بيبعت verification email تلقائياً
        event(new Registered($user));

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    // ==================== Login ====================

    public function login(array $credentials): array
    {
        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['البريد الإلكتروني أو كلمة المرور غير صحيحة'],
            ]);
        }

        if (! $user->is_active) {
            throw ValidationException::withMessages([
                'email' => ['الحساب موقوف، تواصل مع الدعم'],
            ]);
        }

        // حذف الـ tokens القديمة وعمل token جديد
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    // ==================== Logout ====================

    public function logout(User $user): void
    {
        // حذف الـ token الحالي بس
        $user->currentAccessToken()->delete();
    }

    public function logoutAll(User $user): void
    {
        // حذف كل الـ tokens (كل الأجهزة)
        $user->tokens()->delete();
    }

    // ==================== Forgot Password ====================

    public function forgotPassword(string $email): string
    {
        $status = Password::sendResetLink(['email' => $email]);

        if ($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return $status;
    }

    // ==================== Reset Password ====================

    public function resetPassword(array $data): string
    {
        $status = Password::reset(
            [
                'email'                 => $data['email'],
                'password'              => $data['password'],
                'password_confirmation' => $data['password_confirmation'],
                'token'                 => $data['token'],
            ],
            function (User $user, string $password) {
                $user->forceFill(['password' => $password])->save();
                $user->tokens()->delete(); // logout من كل الأجهزة
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return $status;
    }
}
