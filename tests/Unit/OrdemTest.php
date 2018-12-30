<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 27/12/18
 * Time: 21:37
 */

namespace Tests\Unit;

use App\Ordem;
use App\Produto;
use App\Services\Correios;
use Illuminate\Support\Collection;
use Tests\TestCase;

class OrdemTest extends TestCase
{

    /** @test
     *  Testa o end-point api.frete
     *
     *  Espera retorno 200 e um array ['valor', 'teste']
     */
    function calcularFreteTest() {
        $carrinho = factory(Produto::class, 3)
            ->create(['preco' => 20.00])
            ->each(function($p) { $p->quantidade = $p->id; })
            ->toArray();

        $cep = '04777-000'; //Interlagos

        $this->post(route('api.frete'), compact('cep', 'carrinho'))
            ->assertStatus(200)
            ->assertJsonStructure( ['valor','prazo'] );
    }

    /** @test
     *  Testa a criação de ordem
     */
    function ordemTest() {
        $produtos = factory(Produto::class, 3)->create(['preco' => 20.00, 'estoque' => 100]);

        $ordem = $this->criaEValidaOrdem($produtos);

        $this->validaDescontoEstoque($ordem);

        $this->validaValorTotal($ordem);
    }

    private function validaValorTotal($ordem) {
        $this->assertEquals(20*6 + $ordem['entrega']['frete'], $ordem['valor_total']);
    }

    private function criaEValidaOrdem($produtos) {
        $cliente = $this->getVisitante();

        $entrega = (object)[
            'cep' => '04777-000',
            'endereco' => $this->faker->address
        ];

        $carrinho = $produtos
            ->each(function(&$p) { $p->quantidade = $p->id; })
            ->toArray();

        $response = $this->post(route('api.ordem'), compact('carrinho', 'cliente', 'entrega'));

        $response->assertStatus(201)
            ->assertJsonStructure([
                'valor_total',
                'cliente' => [
                    'nome',
                    'email',
                    'endereco',
                    'cep'
                ],
                'entrega' => [
                    'endereco',
                    'cep',
                    'frete',
                ],
                'carrinho'
            ]);

        return $response->original;
    }

    private function validaDescontoEstoque($ordem) {

        $estoque_indexado_por_id = Produto::findMany(collect($ordem['carrinho'])->pluck('id'))
            ->pluck('estoque', 'id')
            ->toArray();

        $this->assertEquals( $estoque_indexado_por_id, [1 => 99, 2 => 98, 3 => 97] );
    }

    function PedidoProdutosWithUserIdTest() {
        // TODO
    }

    private function getVisitante() {
        return [
            'nome' => $this->faker->name,
            'email' => $this->faker->email,
            'telefone' => $this->faker->phoneNumber,
            'endereco' => $this->faker->address,
            'cep' => $this->faker->postcode
        ];
    }
}
