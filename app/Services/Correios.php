<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 28/12/18
 * Time: 14:55
 */

namespace App\Services;


use App\Produto;
use Illuminate\Support\Collection;

class Correios
{

    static public function buscaCep($cep) {
        $url = "https://viacep.com.br/ws/{$cep}/json/";
        return json_decode(file_get_contents($url));
    }

    static public function calcularFrete($cep_origem, $cep_destino, Collection $produtos) {

        $quantidade_indexada = $produtos->pluck('quantidade','id');
        $produtos = Produto::query()->findMany($produtos->pluck('id'));
        $valor_declarado = $produtos->sum(function($p) use ($quantidade_indexada) { return $p->preco * $quantidade_indexada[$p->id]; });

        $peso = $quantidade_indexada->sum();

        $query = http_build_query([
            'nCdEmpresa' => '',
            'sDsSenha' => '',
            'nCdServico' => '04510',
            'sCepOrigem' => $cep_origem,
            'sCepDestino' => $cep_destino,
            'nVlPeso' => $peso,
            'nCdFormato' => 1,
            'nVlComprimento' => 20,
            'nVlAltura' => 5,
            'nVlLargura' => 15,
            'sCdMaoPropria' => 's',
            'nVlValorDeclarado' => $valor_declarado,
            'sCdAvisoRecebimento' => 's',
            'nVlDiametro' => 0,
            'StrRetorno' => 'xml'
        ]);

        $url = 'http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?'.$query;

        $frete_calcula = simplexml_load_string(file_get_contents($url));

        $frete = $frete_calcula->cServico;

        if($frete->Erro == '7') {
            throw new \Exception('Serviço temporariamente indisponível, tente novamente mais tarde.', 503);
        }

        if ($frete->Erro != '0') {
            throw new \Exception('Fatal Error. '.$frete->Erro, 500);
        }

        return [
            'valor' => (float)str_replace(",", ".", (string)$frete->Valor),
            'prazo' => (int)((string)$frete->PrazoEntrega)
        ];
    }
}