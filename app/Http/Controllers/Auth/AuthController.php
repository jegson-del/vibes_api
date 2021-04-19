<?php

namespace App\Http\Controllers\Auth;

use App\Events\NewRegistration;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\ResetRequest;
use App\Http\Resources\StatusResponse;
use App\Http\Resources\UserResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function reset(ResetRequest $credentials)
    {
        $credentials = $credentials->request->all();

        $data = DB::table('password_resets')
            ->where('email', $credentials['email'])
            ->where('created_at', '>', now()->subMinute(60))
            ->orderBy('created_at', 'DESC')
            ->first();


        if(!Hash::check($credentials['token'], $data->token)) {
            abort(404);
        }

        User::where('email', $credentials['email'])
            ->firstOrFail()
            ->forceFill([
                'password' => Hash::make($credentials['password']),
            ])->save();

        return redirect()->back()->with('message', 'Update Successful');
    }
}
