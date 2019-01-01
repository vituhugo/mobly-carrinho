<?php

namespace App\Http\Controllers\Api;

use App\Cliente;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class ClienteController extends Controller
{
    public function get(Request $request) {

        $objectToken = JWTAuth::setToken($request->get('token'));
        $id = JWTAuth::decode($objectToken->getToken())->get('sub');


        $user = User::with('cliente')->find($id)->first();

        if (!$user) {
            return response()->json($user, 404);
        }

        return response()->json([
            'nome' => $user->name,
            'email' => $user->email,
            'telefone' => $user->cliente->telefone,
            'endereco' => $user->cliente->endereco,
            'cep' => $user->cliente->cep
        ]);
    }

    public function registrar(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|regex:/^.+@.+$/i',
            'nome' => 'required',
            'senha' => 'required|min:6',
            'endereco' => 'required',
            'cep' => 'required|regex:/^[0-9]{5}-?[0-9]{3}$/',
            'telefone' => 'required|regex:/^\(?[0-9]{2}\)? ?9? ?[0-9]{4}-? ?[0-9]{4}$/'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }

        $password = Hash::make($request->input('senha'));
        $email = $request->input('email');
        $name = $request->input('nome');

        $user = \App\User::create(compact('name', 'email', 'password'));

        $dados = $request->only('endereco', 'telefone', 'cep');
        $dados['user_id'] = $user->id;

        $cliente = Cliente::create($dados);
        $cliente->user;

        $dados = $cliente->toArray();

        unset($dados['user']['password']);

        $token = User::getToken($email, $request->input('senha'));
        if (!isset($token['access_token'])) {
            return response()->json(['Credenciais não encontradas.'], 401);
        }
        return response()->json(array_merge($token, ['user' => [
            'nome' => $cliente->user->name,
            'email' => $cliente->user->email,
            'telefone' => $cliente->telefone,
            'endereco' => $cliente->endereco,
            'cep' => $cliente->cep
        ]]), 201);
    }
}
