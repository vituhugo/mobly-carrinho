<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 30/12/18
 * Time: 14:37
 */

namespace Tests\Unit;

use App\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * @test
     */
    function getTokenTest() {
        factory(User::class)->create(['email' => 'teste@teste.com']);
        $this->post(route('api.token'), ['email' => 'teste@teste.com', 'senha' => 'secret'])->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in'
        ]);

        $this->post(route('api.token'), ['email' => 'teste@teste.com', 'senha' => 'secret2'])->assertStatus(401);
        $this->post(route('api.token'), ['email' => 'teste@teste', 'senha' => 'secret'])->assertStatus(401);
    }

}
