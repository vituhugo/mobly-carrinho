<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 28/12/18
 * Time: 10:57
 */

namespace Tests\Unit;

use App\Produto;
use Tests\TestCase;

class ProdutoTest extends TestCase
{
    /** @test */
    public function getTest() {
        $produto = factory(Produto::class)->create();
        $this->get(route('api.produtos.get', $produto->id))->assertJson($produto->toArray());
    }

    /**
     * ** Teste Removido **
     *
     * Não é possível testar atualmente devido a um problema do sqlite não entender o index fulltext
     *
     */
    public function getListaTest() {
        $p1 = factory(Produto::class)->create(['nome' => 'vassoura amarela']);
        $p2 = factory(Produto::class)->create(['nome' => 'vinagre grande']);
        $p3 = factory(Produto::class)->create(['nome' => 'corda longa']);
        $p4 = factory(Produto::class)->create(['nome' => 'beija-flor']);
        $p5 = factory(Produto::class)->create(['nome' => '4 coisas bem diferentes']);

        $response = $this->get('api.produtos.search', [ 'ipp' => 2, 'order' => 'name', 'order_type' => 'desc' ]);
        $response->assertJsonStructure(
                [
                    'items',
                    'length',
                    'current_page',
                    'pages',
                ]
            );

        $data = $response->original;
        $this->assertEquals($data['items'][0]['id'], $p5->id);
        $this->assertEquals($data['items'][1]['id'], $p4->id);
        $this->assertEquals($data['pages'], 3);
        $this->assertEquals($data['current_page'], 0);

        $data = $this->get('api.produtos', [ 'ipp' => 2, 'order_by' => 'name', 'order_type' => 'asc', 'page' => 1 ])->original;
        $this->assertEquals($data['items'][0]['id'], $p3->id);

        $data = $this->get('api.produtos', [ 'q' => 'vassoura', 'ipp' => 2, 'order' => 'name', 'order_type' => 'asc', 'page' => 1 ])->original;
        $this->assertEquals($data['items'][0], $p1->toArray());

        $data = $this->get('api.produtos', [ 'q' => 'm' ])->original;
        $this->assertEquals($data['length'], 2);
    }
}
