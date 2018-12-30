<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 27/12/18
 * Time: 22:25
 */

namespace App;

use App\Traits\FullTextTrait;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Produto
 * @method \Illuminate\Database\Eloquent\Builder search($string)
 * @package App
 */
class Produto extends Model
{
    use HasTimestamps, SoftDeletes, FullTextTrait;

    protected $appends = array('quantidade');

    protected $searchable = [
        'nome',
        'descricao',
    ];

    private $quantidade;

    public function descontarEstoque($quantidade) {

        $this->estoque -= $quantidade;

        if ($this->estoque < 0) throw new \Exception("Estoque indisponível ou inexistente. (Estoque: {$this->estoque})", 405);

        $this->save();
    }

    public function buy(int $quantidade) {
        if ($this->estoque < $quantidade) {
            throw new \Exception('Estoque insuficiente ou indisponível', 403);
        }

        $this->estoque -= $quantidade;
        $this->save();

        return true;
    }

    public function categorias()
    {
        return $this->belongsToMany('App\Categoria', 'produto_categoria');
    }

    protected function getQuantidadeAttribute() {
        if ($this->quantidade === null && isset($this->pivot) && isset($this->pivot->quantidade)) {
            $this->quantidade = $this->pivot->quantidade;
        }

        return $this->quantidade;
    }

    protected function setQuantidadeAttribute($val) {
        return $this->quantidade = $val;
    }
}