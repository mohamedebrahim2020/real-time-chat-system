<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
		// my logic here depend on user can sign in or up from the first time
        $user = User::firstOrCreate(
            ['email' => $request->email],
            ['email' => $request->email, 'name' => Str::random(8), 'password' => hash("sha256", $request->password)]
        );

        $token = $user->createToken('chat');

        return ['token' => $token->plainTextToken];

    }
}
