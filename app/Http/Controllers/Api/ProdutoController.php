<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Produto;
use Illuminate\Http\Request;

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
        $list = Produto::search($term)
            ->with('categorias')
            ->orderBy($order_by, $order_type)
            ->paginate($itens_per_page);

        return response()->json($list);
    }
}
