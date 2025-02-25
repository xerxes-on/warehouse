<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use App\Http\Traits\CanSendJsonResponse;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticateUserController extends Controller
{
    use CanSendJsonResponse;

    /**
     * Handle user login using Sanctum tokens.
     *
     * @throws ValidationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        Auth::login($user);
        session(['user_role' => auth()->user()->role->name]);
        $token = $user->createToken('client_token')->plainTextToken;

        return $this->sendResponse(['token' => $token, 'user' => $user->load('cart')]);
    }

    /**
     * Revoke the current user's token.
     */
    public function logout(Request $request): JsonResponse
    {
        Auth::logout();
        $request->user()->currentAccessToken()->delete();
        return $this->sendResponse('Successfully logged out');
    }

    /**
     * Register a new user and issue a Sanctum token.
     */
    public function register(RegisterUserRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => Role::where('name', 'client')->first()->id,
        ]);

        event(new Registered($user));
        Auth::login($user);
        session(['user_role' => auth()->user()->role->name]);
        $token = $user->createToken('client_token')->plainTextToken;
        return $this->sendResponse(['token' => $token, 'user' => $user->load('cart')]);
    }

    /**
     * Update the authenticated user's password.
     */
    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return $this->sendResponse('Success');
    }

    /**
     * Send a password reset link.
     *
     * @throws ValidationException
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $status = \Illuminate\Support\Facades\Password::sendResetLink(
            $request->only('email')
        );

        if ($status !== \Illuminate\Support\Facades\Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return $this->sendResponse(['status' => __($status)]);
    }
}
