<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 27/12/18
 * Time: 22:58
 */

use Faker\Generator as Faker;

$factory->define(App\Produto::class, function (Faker $faker) {
    $nome = $faker->name;
    return [
        'nome' => $nome,
        'estoque' => 99,
        'slug' => str_slug($nome),
        'descricao' => $faker->text(200),
        'imagem' => $faker->image('public/images/storage', 150, 150, null, false),
        'preco' => $faker->randomFloat(2, 10, 100)
    ];
});