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

Route::get('/produto/{produto}', 'ProdutoController@get')->name('api.produtos.get');

Route::get('/produto', 'ProdutoController@buscar')->name('api.produtos.buscar');

Route::post('/ordem', 'OrdemController@criar')->name('api.ordem');

Route::post('/frete', 'OrdemController@calcularFrete')->name('api.frete');

Route::post('/registrar', 'ClienteController@registrar')->name('api.registrar');

Route::post('auth/get-token', 'AuthController@getToken')->name('api.token');

Route::middleware('jwt.auth')->get('/clientes', 'ClienteController@get')->name('api.clientes');