<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use phpDocumentor\Reflection\Types\Boolean;
use PhpOption\None;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function test()
    {
        return 1;
    }

    public function register(Request $request)
    {
        return User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'sex' => $request->input('sex'),
            'weight' => $request->input('weight'),
            'profile' => $request->input('profile'),
            'birth' => $request->input('birth'),
            'introduce' => $request->input('introduce'),
            'location' => $request->input('location'),
            'mmr' => 0,
            'win' => 0,
            'loss' => 0,
            'percentage' => 0
        ]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response([
                'message' => 'Invalid credentials!'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $login_user = Auth::user();

        $token = $login_user->createToken('token')->plainTextToken;
        $cookie = cookie('jwt', $token, 60 * 24, "", "2yubi.tk"); // 1 day


        $user = User::with(['followings', 'followers', 'posts'])->find($login_user->id);

        return response([
            'message' => $token,
            'user' => $user
        ])->cookie($cookie);
    }

    public function user()
    {
        // if (Auth::user()) {
        //     return true;
        // } else {
        //     return;
        // }
        return Auth::user();
        // $id = Auth::user()->getAttribute('id');
        // $user = User::with(['followings', 'followers', 'posts'])->find($id);
        // // $user = User::with(['followings'])->find($id);
        // return $user;
    }

    public function logout()
    {
        $cookie = Cookie::forget('jwt');

        return response([
            'message' => 'Success'
        ])->withCookie($cookie);
    }
}
