@extends('layout.master')
@section('content')
    <div class="container" id="app-carrinho">

        <ul class="list-group pt-5">
            <li class="list-group-item list-group-item-primary">
                <h2 class="text-center m-0">
                    Carrinho
                </h2>
            </li>
            <li class="list-group-item text-center py-5" v-show="carrinho.length === 0">
                <h3>Não há produtos em seu carrinho.</h3>
            </li>
            <li class="list-group-item" v-for="produto in carrinho">
                <div class="row justify-content-between">
                    <div class="col-5 d-flex">
                        <div class="col-auto">
                            <img :src="'/images/storage/'+produto.imagem" width="50" height="50" />
                        </div>
                        <div class="text-left">
                            <h3 class="mb-0">@{{ produto.nome }}</h3>
                            <ul class="list-inline">
                                <li  class="list-inline-item">
                                    unit.: R$ @{{ formatarPreco(produto.preco) }}
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-5 d-flex justify-content-end">
                        <label class="form-group d-flex">
                            <span>Qtd:</span>
                            <input v-model="produto.quantidade" type="number" class="form-control ml-2" style="width: 100px">
                        </label>
                        <div class="ml-4">
                            <button class="btn btn-dark" @click="refreshTotal(true)">
                                Atualizar
                                <i class="fas fa-sync-alt px-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </li>
            <li class="list-group-item list-group-item-secondary">
                <div class="row justify-content-between">
                    <form class="col-3">
                        <label class="form-group">
                            <span>Calcular Frete:</span>
                            <input type="text" v-model="cep" class="form-control" placeholder="Digite seu cep..." :disabled="!carrinho.length" />
                        </label>

                        <small class="m-0 text-white text-nowrap" v-if="prazo">
                            O Frete de R$ @{{ formatarPreco(frete) }} no prazo de @{{ prazo }} dias.
                        </small>
                    </form>
                    <div class="col-3">
                        <h4>Carrinho: </h4>
                        <h3 class="text-primary">R$ @{{ formatarPreco(total) }}</h3>
                    </div>
                    <div class="col-3">
                        <h4>Total: </h4>
                        <h3 class="text-primary">R$ @{{ formatarPreco(total + frete) }}</h3>
                    </div>
                </div>
            </li>
        </ul>

        <div class="d-flex justify-content-end pt-4">

                <a href="/finalizar-compra" class="btn btn-danger" style="font-size: 2em;" @click="finalizarCompra">
                    Finalizar Compra
                    <i class="fa fa-credit-card mx-2"></i>
                </a>
        </div>
    </div>
@endsection