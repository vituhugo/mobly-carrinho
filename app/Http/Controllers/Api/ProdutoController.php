<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProdutoController extends Controller
{
    public function get(Produto $produto) {
        return response()->json($produto, 200);
    }

    public function buscar(Request $request) {
        $itens_per_page = $request->input('ipp', 10);
        $order_by = $request->input('order_by', 'nome');
        $order_type = $request->input('order_type', 'asc');

        $term = $request->input('q', '');

        $page = $request->input('page', 1);

        $cache_key = "search:".implode("_", [$page, $itens_per_page, $order_by, $order_type, $term]);

        //Cacheia os resultados sem busca especifica.
        $list = $term

            ? Produto::search($term)
                ->with('categorias')
                ->orderBy($order_by, $order_type)
                ->paginate($itens_per_page)

            : Cache::remember($cache_key, 60, function() use($itens_per_page, $order_by, $order_type) {
                return Produto::query()
                    ->with('categorias')
                    ->orderBy($order_by, $order_type)
                    ->paginate($itens_per_page);
            });

        return response()->json($list);
    }
}
