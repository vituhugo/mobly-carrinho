<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/produto', 'ProdutoController@buscar')->name('api.produtos.buscar');
Route::get('/produto/{produto}', 'ProdutoController@get')->name('api.produtos.get');


Route::put('/ordem', 'OrdemController@criar')->name('api.ordem');
Route::post('/calcular-frete', 'OrdemController@calcularFrete')->name('api.frete');
Route::get('/buscar-cep/{cep}', 'OrdemController@buscaCep')->name('buscar-cep');

Route::put('/cliente', 'ClienteController@criar')->name('api.registrar');
Route::middleware('jwt.auth')->get('/perfil', 'ClienteController@perfil')->name('api.clientes');

Route::post('auth/get-token', 'AuthController@getToken')->name('api.token');
