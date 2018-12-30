<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');


Artisan::command('new_user {name} {email} {password}',function($name, $email, $password) {
    $password = Hash::make($password);
    $user = \App\User::create(compact('name', 'email', 'password'));
    $this->info("CREATE USER WITH ID: {$user->id}");
});