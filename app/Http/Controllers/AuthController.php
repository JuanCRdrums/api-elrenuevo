<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->all();

        if (Auth::attempt(['login' => $data['login'], 'password' => $data['password']])){
            $user = Auth::user();
            return ['error' => 0, 'api_key' => $user->api_key];
        }
        else
            return ['error' => 1, 'api_key' => ''];
    }
}
