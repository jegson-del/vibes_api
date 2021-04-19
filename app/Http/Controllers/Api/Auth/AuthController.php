<?php

namespace App\Http\Controllers\Api\Auth;

use App\Events\NewRegistration;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\ResetRequest;
use App\Http\Resources\StatusResponse;
use App\Http\Resources\UserResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegistrationRequest $request): UserResponse
    {
        $user = User::make($request);

        $token = $user->createToken($request->device_name, [User::ROLE_SUBSCRIBER])->plainTextToken;

        event(new NewRegistration($user));

        return new UserResponse($user, $token);
    }

    public function login(LoginRequest $request): UserResponse
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken($request->device_name, [User::ROLE_SUBSCRIBER])->plainTextToken;

        return new UserResponse($user, $token);
    }

    public function logout(Request $request): StatusResponse
    {
        $request->user()->tokens()->delete();
        return new StatusResponse('success');
    }

    public function forgot(Request $request): StatusResponse
    {
        $credentials = $request->validate(['email' => 'required|email']);
        Password::sendResetLink($credentials);

        return new StatusResponse('success');
    }

    public function deleteAccount(Request $request): StatusResponse
    {
        $user = $request->user();
        $user->tokens()->delete();
        $user->delete();

        return new StatusResponse(
            'success',
            ['message' => 'User deleted successfully']
        );
    }
}
