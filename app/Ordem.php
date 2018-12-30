<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 27/12/18
 * Time: 15:13
 */

namespace App;

use App\Services\Correios;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Ordem extends Model
{
    use SoftDeletes, HasTimestamps;

    protected $table = 'ordens';

    protected $fillable  = [
            'user_id',
            'valor_frete',
            'valor_total',

            'ordem_nome',
            'ordem_email',
            'ordem_telefone',
            'ordem_endereco',
            'ordem_cep',

            'entrega_endereco',
            'entrega_cep',
        ];

    public static function criar(Collection $carrinho, $cliente, \stdClass $entrega) {
        $cliente = is_integer($cliente) ? Cliente::findByUserId($cliente) : (object)$cliente;

        $produtos_indexed = $carrinho->pluck('quantidade', 'id');

        $produtos = Produto::query()->findMany($produtos_indexed->keys());

        $produtos = $produtos->map(function(Produto $p) use ($produtos_indexed) { $p->quantidade = $produtos_indexed[$p->id]; return $p; });

        try {
            DB::beginTransaction();
            $valor_frete = (float)str_replace(',', '.', self::calcularFrete($entrega->cep, $carrinho)['valor']);

            $ordem = Ordem::create([
                'user_id' => isset($cliente->id) ? $cliente->id : null,
                'valor_frete' => $valor_frete,
                'valor_total' => round($produtos->sum(function($p) use ($produtos_indexed) { return $p->preco * $produtos_indexed[$p->id]; }) + $valor_frete, 2),

                'ordem_nome' => $cliente->nome,
                'ordem_email' => $cliente->email,
                'ordem_telefone' => $cliente->telefone,
                'ordem_endereco' => $cliente->endereco,
                'ordem_cep' => $cliente->cep,

                'entrega_endereco' => $entrega->endereco,
                'entrega_cep' => $entrega->cep,
            ]);

            $produtos->each(function(Produto $p) use ($produtos_indexed, $ordem) {
                $p->descontarEstoque($produtos_indexed[$p->id]);
                $ordem->produtos()->sync($produtos_indexed->map(function($i) { return ['quantidade' => $i];}));
            });
        }

        catch(\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();

        $ordem->produtos->map(function($p) { return $p->quantidade = $p->pivot->quantidade; });

        return $ordem;
    }

    public function produtos() {
        return $this->belongsToMany('App\Produto', 'ordem_produto')->withPivot('quantidade');
    }

    public static function calcularFrete($cep, Collection $produtos) {
        return Correios::calcularFrete(config('services.correios.cep_origem'), $cep, $produtos);
    }

}