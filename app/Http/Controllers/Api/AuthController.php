<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class AuthController extends Controller
{
    public function getToken(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'senha' => 'required'
        ]);

        if (!$validator) {
            return response()->json($validator->errors(), 400);
        }

        $response = User::getToken($request->input('email'), $request->input('senha'));

        if (!isset($response['access_token'])) {
            return response()->json($response, 401);
        }
        return response()->json($response, 200);
    }
}
