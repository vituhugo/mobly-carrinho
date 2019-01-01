<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Ordem;
use App\Services\Correios;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

use Validator;

class OrdemController extends Controller
{
    use ValidatesRequests;

    public function criar(Request $request) {
        $validator = Validator::make($request->all(), [
            'carrinho' => 'required|array',
            'cliente.nome' => 'required_without:token',
            'cliente.email' => 'required_without:token|regex:/^.+@.+$/i',
            'cliente.telefone' => "required_without:token|regex:/^\(?[0-9]{2}\)? ?9? ?[0-9]{4}-? ?[0-9]{4}$/",
            'cliente.endereco' => 'required_without:token',
            'cliente.cep' => 'required_without:token|regex:/^[0-9]{5}-?[0-9]{3}$/',
            'entrega.endereco' => 'string',
            'entrega.cep' => 'regex:/^[0-9]{5}-?[0-9]{3}$/',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 400);

        $ordem = Ordem::criar(
            collect($request->input('carrinho')),
            $request->has('user_id')
                ? $request->input('user_id')
                : (object)$request->input('cliente'),
            (object)$request->input('entrega'));

        $response = [
            'ordem_id' => $ordem->id,
            'valor_total' => $ordem->valor_total,
            'cliente' => [
                'nome' => $ordem->ordem_nome,
                'email' => $ordem->ordem_email,
                'endereco' => $ordem->ordem_endereco,
                'cep' => $ordem->ordem_cep,
            ],
            'entrega' => [
                'endereco' => $ordem->entrega_endereco,
                'cep' => $ordem->entrega_cep,
                'frete' => $ordem->valor_frete
            ],
            'carrinho' => $ordem->produtos
                ->map(function($p) {
                    $p['quantidade'] = $p->pivot->quantidade;
                    $p = collect($p)->only(
                        'id',
                        'nome',
                        'slug',
                        'descricao',
                        'imagem',
                        'preco',
                        'quantidade');

                    return $p;
                })
        ];

        return response()->json($response, 201);
    }

    public function buscaCep($cep) {
        return response()->json(Correios::buscaCep($cep));
    }

    public function calcularFrete(Request $request) {
        $validator = Validator::make($request->all(), [
            'cep' => 'required|string',
            'carrinho' => 'required|array',
            'carrinho.*.id' => 'required|integer',
            'carrinho.*.quantidade' => 'required|integer'
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 400);

        return response()->json(Correios::calcularFrete(
            config('services.correios.cep_origem'),
            $request->input('cep'),
            collect($request->input('carrinho'))
        ), 200);
    }
}
