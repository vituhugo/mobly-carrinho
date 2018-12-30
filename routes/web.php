<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/cadastrar', function () {
    return view('cadastrar');
})->name('cadastrar');

Route::get('/entrar', function () {
    return view('entrar');
})->name('entrar');

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/produtos', function () {
    return view('produtos');
})->name('produtos');

Route::get('/carrinho', function () {
    return view('carrinho');
})->name('carrinho');

Route::get('/finalizar-compra', function () {
    return view('finalizar-compra');
})->name('finalizar');
