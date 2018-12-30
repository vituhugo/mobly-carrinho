<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 30/12/18
 * Time: 14:36
 */

namespace Tests\Unit;

use App\Cliente;
use App\User;
use Nexmo\Client;
use Tests\TestCase;

class ClienteTest extends TestCase
{
    /**
     * @test
     */

    public function getTest() {
        $cliente = factory(Cliente::class)->create();

        $access_token = $this->post(route('api.token'), ['email' => $cliente->user->email, 'senha' => 'secret'])->original['access_token'];
        $this->get(route('api.clientes', ['token' => $access_token]))
            ->assertStatus(200)
            ->assertJsonStructure([
            'nome',
            'email',
            'telefone',
            'endereco',
            'cep'
        ]);
    }

    /**
     * @test
     */
    public function registrarTest() {
        $params = [
            'nome' => 'Nome Teste',
            'email' => 'teste@teste.com.br',
            'senha' => 'secret',
            'endereco' => 'Rua alguma coisa lago blablabal',
            'telefone' => '11999995555',
            'cep' => '04457090',
        ];

        $this->post(route('api.registrar'), $params)->assertStatus(201)
        ->assertJsonStructure([
            'user' => [
                'nome',
                'email',
                'telefone',
                'endereco',
                'cep'
            ]
        ]);
    }
}
