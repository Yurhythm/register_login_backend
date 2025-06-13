<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            $datas['access_token']  =  $token;
            $datas['name']   =  $user->name;
            $datas['email']  =  $user->email;

            return response()->json([
                'success' => true,
                'message' => 'Registration Success',
                'data' => $datas
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 422);
        }

    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid login credentials',
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        $datas['access_token']  =  $token;
        $datas['name']   =  $user->name;
        $datas['email']  =  $user->email;

        return response()->json([
            'success' => true,
            'message' => 'Login Success',
            'data' => $datas
        ], 200);
    }

    public function logout(Request $request)
    {
        if ($request->user()!=null) {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'success' => true,
                'message' => 'Logout Success',
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'Already Logout',
        ], 200);
    }
}
