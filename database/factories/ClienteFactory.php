<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 27/12/18
 * Time: 22:58
 */

use Faker\Generator as Faker;
use Faker\Factory as FakerF;
$factory->define(App\Cliente::class, function () {

    $faker = FakerF::create('pt_BR');

    return [
        'user_id' => factory(App\User::class)->create()->id,
        'telefone' => $faker->phoneNumber,
        'endereco' => $faker->address,
        'cep' => $faker->postcode
    ];
});