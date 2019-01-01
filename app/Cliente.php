<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 27/12/18
 * Time: 15:13
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'endereco',
        'cep',
        'telefone',
        'user_id'
    ];

    protected $appends = array('nome', 'email');

    public function user() {
        return $this->belongsTo('App\User');
    }

    public static function findByUserId(int $id) {
        return self::query()->where('user_id', $id)->first();
    }

    protected function getNomeAttribute() {
        return $this->user->nome;
    }

    protected function getEmailAttribute() {
        return $this->user->email;
    }

    protected function setTelefoneAttribute($value) {
        $this->attributes['telefone'] = preg_replace('/[^0-9]/', '', $value);
    }

    protected function setCepAttribute($value) {
        $this->attributes['cep'] = preg_replace('/[^0-9]/', '', $value);
    }
}