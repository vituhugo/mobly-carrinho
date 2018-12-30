<?php

use Illuminate\Database\Seeder;

class ProdutoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categorias = App\Categoria::all();

        factory(App\Produto::class, 50)->create()->each(function($produto) use ($categorias) {
            $produto->categorias()->sync(
                $categorias->shuffle()->slice(0, random_int(1, 3))->pluck('id')->toArray()
            );
        });
    }
}
