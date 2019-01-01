@extends('layout.master')
@section('header')
    <div class="banner" style="background: url('/images/banner.jpg') no-repeat center center;background-size: cover;width: 100%; height: 35vw;"></div>
@endsection
@section('content')
    <div class="container" id="app">

        <h2 class="text-center pt-5">
            Produtos
        </h2>


        <div class="form-inline row mb-3">
            <div class="col-10">
                <input class="form-control" type="search" placeholder="Buscar" aria-label="Search" style="width: 100%" v-model="input.busca">
            </div>

            <div class="col-2">
                <button class="btn btn-outline-info my-2 my-sm-0" style="width: 100%" @click="busca = input.busca">Buscar</button>
            </div>
        </div>

        <div class="card">
            <div class="card-body row">
                <div class="col-3 d-flex">
                    <h3 class="mb-0 align-items-center d-flex">Organizar por:</h3>
                </div>
                <div class="col-9 form-group mb-0 d-flex justify-content-between">
                    <div>
                        <div class="d-flex">
                            <select class="form-control mr-3" v-model="input.ordenado_por">
                                <option value="nome">Nome</option>
                                <option value="preco">Preço</option>
                            </select>

                            <select class="form-control" style="width: 140px" v-model="input.ordenado_tipo">
                                <option value="asc">crescente</option>
                                <option value="desc">decrescente</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex">
                        <span class="mr-3 d-flex align-items-center"> Items&nbsp;por&nbsp;página </span>
                        <select class="form-control" v-model="input.itens_por_pagina">
                            <option :value="10">10</option>
                            <option :value="20">20</option>
                            <option :value="30">30</option>
                        </select>

                        <button class="btn btn-secondary ml-4" @click="atualizarOrdenacao()">
                            Ok!
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mt-3">
            <nav>
                <ul class="pagination" v-if="paginate_info.last_page > 1">
                    <li class="page-item" :class="{ disabled: !paginate_info.prev_page_url}">
                        <a class="page-link" href="#" tabindex="-1" @click="pagina_atual--;$event.preventDefault()" :disabled="!paginate_info.prev_page_url">Voltar</a>
                    </li>

                    <li class="page-item" :class='{ "active": n == paginate_info.current_page }'  v-for="n in paginate_info.last_page">
                        <a class="page-link" @click="pagina_atual = n">
                            @{{n}}
                            <span v-if="n == paginate_info.current_page" class="sr-only">(current)</span>
                        </a>
                    </li>

                    <li class="page-item">
                        <a class="page-link" :class="{ disabled: !paginate_info.next_page_url}" @click="pagina_atual++;$event.preventDefault()" :disabled="!paginate_info.prev_page_url">Avançar</a>
                    </li>
                </ul>
            </nav>
        </div>

        <ul class="list-group">
            <li class="list-group-item" v-for="produto in produtos">
                <div class="row justify-content-between">
                    <div class="col-9 d-flex">
                        <div class="col-auto">
                            <img :src="'/images/storage/'+produto.imagem" width="150" height="150" />
                        </div>
                        <div>
                            <h3>@{{ produto.nome }}</h3>
                            <p>
                                @{{ produto.descricao }}
                            </p>
                            <ul class="list-inline">
                                <li class="list-inline-item" v-for="categoria in produto.categorias">
                                    <button class="btn btn-secondary px-1 py-0">
                                        @{{ categoria.name }}
                                    </button>
                                </li>
                            </ul>
                            <hr>
                            <ul class="list-inline text-right">
                                <li  class="list-inline-item">
                                    Preço: @{{ formatarPreco(produto.preco) }}
                                </li>
                                <li class="list-inline-item">
                                    Estoque: @{{ produto.estoque }}
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-3 d-flex flex-column justify-content-end">
                        <label class="form-group d-flex justify-content-end">
                            <span>Qtd:</span>
                            <input type="number" class="form-control ml-2" style="width: 100px" v-model="produto.quantidade">
                        </label>
                        <button class="btn btn-dark mt-3" @click="addCarrinho(produto); produto.quantidade = 0">
                            Carrinho
                            <i class="fas fa-cart-arrow-down pr-1"></i>
                        </button>
                    </div>
                </div>
            </li>
        </ul>
        <div class="row justify-content-center mt-3">
            <nav>
                <ul class="pagination" v-if="paginate_info.last_page > 1">
                    <li class="page-item" :class="{ disabled: !paginate_info.prev_page_url}">
                        <a class="page-link" href="#" tabindex="-1" @click="pagina_atual--;$event.preventDefault()" :disabled="!paginate_info.prev_page_url">Voltar</a>
                    </li>

                    <li class="page-item" :class='{ "active": n == paginate_info.current_page }'  v-for="n in paginate_info.last_page">
                        <a class="page-link" @click="pagina_atual = n">
                            @{{n}}
                            <span v-if="n == paginate_info.current_page" class="sr-only">(current)</span>
                        </a>
                    </li>

                    <li class="page-item">
                        <a class="page-link" :class="{ disabled: !paginate_info.next_page_url}" @click="pagina_atual++;$event.preventDefault();" :disabled="!paginate_info.prev_page_url">Avançar</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
@endsection