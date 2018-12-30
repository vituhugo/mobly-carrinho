<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Notifiable, CanResetPassword, Authenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function getToken($email, $senha) {
        $credentials = compact('email', 'senha');

        $user = User::where('email', $credentials['email'])->first();

        if(!$user || !Hash::check($credentials['senha'], $user->password)) {
            return ['message' => 'Credenciais invalidas'];
        }

        $token = JWTAuth::fromUser($user);

        $objectToken = JWTAuth::setToken($token);
        $expiration = JWTAuth::decode($objectToken->getToken())->get('exp');

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $expiration
        ];
    }

    public function cliente() {
        return $this->hasOne('App\Cliente');
    }
}
