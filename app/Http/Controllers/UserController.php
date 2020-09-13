<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'user created',
            'user' => $user
        ]);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $user = User::where('email', $request->email)->first();
        if(!$user) {
            return response()->json([
                'message' => 'login failed'
            ]);
        }

        $isValidPassword = Hash::check($request->password, $user->password);
        if(!$isValidPassword) {
            return response()->json([
                'message' => 'login failed'
            ]);
        }

        $generateApiToken = bin2hex(random_bytes(40));
        $user->update([
            'api_token' => $generateApiToken
        ]);

        return response()->json($user);
    }
}
