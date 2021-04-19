<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResponse;
use App\Models\Event;
use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUser(Request $request)
    {
        return new UserResponse($request->user());
    }

    public function uploadProfilePics(Request $request): UserResponse
    {
        $request->validate(['image' => 'mimes:jpeg,jpg,png|required|max:5000']);

        $user = $request->user();

        if ($user->profilePics) {
            File::removeFile(File::DIR_PROFILE . '/' . $user->profilePics->link);
            $user->profilePics()->delete();
        }

        $file = (new File())->storeFile($request, File::DIR_PROFILE);

        $user->profilePics()->save($file);

        return new UserResponse(User::find($user->id));
    }
}
