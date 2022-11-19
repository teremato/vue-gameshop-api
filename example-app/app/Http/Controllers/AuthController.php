<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @api [auth/registration]
     * @param Request
     * @var name = string
     * @var email = string
     * @var password = string
     * @return [user, token]
     */

    public function registration(Request $request) {

        $fields = $request->validate([
            "name" => "required|string",
            "email" => "required|string|unique:users,email",
            "password" => "required|string|min:6",
        ]);

        $user = User::create($fields);

        $token = $user->createToken('myAppToken')
            ->plainTextToken;

        return response([
            "username" => $user,
            "token" => $token],
        );
    }

    /**
     * @api [auth/login]
     * @param Request
     * @var email = string
     * @var password = string
     * @return [user, token]
     */

    public function login(Request $request) {

        $fields = $request->validate([
            "email" => "required|string",
            "password" => "required|string",
        ]);

        $user = User::where('email', $fields['email'])
            ->first();

        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Bad credits!'
            ], 401);
        }

        $token = $user->createToken('myAppToken')->plainTextToken;

        return response([
            "user" => $user,
            "token" => $token],
        );
    }

    /**
     * @api [auth/logout]
     * @return void
     */

    public function logout(Request $request) {

        auth()->user()->tokens()->delete();

        return response([
            'msg' => 'logout'
        ]);
    }
}
