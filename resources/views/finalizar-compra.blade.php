@extends('layout.master')
@section('content')
    <div class="container" id="app-finalizar">

        <ul class="list-group pt-5">
            <li class="list-group-item list-group-item-primary">
                <h2 class="text-center m-0">
                    Confirmação
                </h2>
            </li>
            <li class="list-group-item" v-for="produto in carrinho">
                <div class="row justify-content-between">
                    <div class="col-12 d-flex">
                        <div class="col-auto">
                            <img :src="'/images/storage/'+produto.imagem" width="50" height="50" />
                        </div>
                        <div class="text-left">
                            <h3 class="mb-0">x@{{ produto.quantidade }} @{{ produto.nome }}</h3>
                            <ul class="list-inline">
                                <li  class="list-inline-item">
                                    unit.: R$ @{{ formatarPreco(produto.preco) }}
                                </li>
                                <li  class="list-inline-item">
                                    <strong>total: R$ @{{ formatarPreco(produto.preco * produto.quantidade) }}</strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </li>
            <li class="list-group-item list-group-item-secondary">
                <div class="row justify-content-between">
                    <div class="offset-6 col-3">
                        <h4>Frete: </h4>
                        <h3 class="text-primary">R$ @{{ formatarPreco(frete) }}</h3>
                    </div>
                    <div class="col-3">
                        <h4>Total: </h4>
                        <h3 class="text-primary">R$ @{{ formatarPreco(total + frete) }}</h3>
                    </div>
                </div>
            </li>
        </ul>

        <div class="card mt-5">
            <div class="card-header">
                <h3 class="mb-0">Dados Pessoais e de entrega</h3>
            </div>
            <div class="card-body">
                <label class="form-group col-12">
                    <span>Nome</span>
                    <input v-model="input.nome" class="form-control" :readonly="esta_logado" />
                </label>
                <label class="form-group col-12">
                    <span>Email</span>
                    <input v-model="input.email" class="form-control" :readonly="esta_logado" />
                </label>

                <label class="form-group col-12">
                    <span>Telefone</span>
                    <input v-model="input.telefone" class="form-control" :readonly="esta_logado" />
                </label>

                <div class="col-12">
                    <div class="row">
                        <label class="form-group col-10">
                            <span>Endereço</span>
                            <input v-model="input.endereco" class="form-control" :readonly="esta_logado" />
                        </label>
                        <label class="form-group col-2">
                            <span>Endereço</span>
                            <input v-model="input.numero" class="form-control" :readonly="esta_logado" />
                        </label>
                    </div>
                </div>
                <label class="form-group col-12">
                    <span>Cep</span>
                    <input v-model="input.cep" class="form-control" :readonly="esta_logado" />
                </label>

                <h4 class="mt-4 mb-0">Dados de Entrega</h4>
                <hr>

                <label class="form-group">
                    <input type="checkbox" class="form-check-inline" v-model="mesmo_endereco" @change="resetCamposEntrega">
                    <span>Mesmo endereço</span>
                </label>
                <div class="col-12">
                    <div class="row">
                        <label class="form-group col-10">
                            <span>Endereço de entrega</span>
                            <input v-model="input.entrega_endereco" class="form-control" :disabled="mesmo_endereco" readonly />
                        </label>
                        <label class="form-group col-2">
                            <span>Número de entrega</span>
                            <input v-model="input.entrega_numero" class="form-control" :disabled="mesmo_endereco" />
                        </label>
                    </div>
                </div>
                <label class="form-group col-12">
                    <span>Cep de entrega</span>
                    <input v-model="input.entrega_cep" class="form-control" :disabled="mesmo_endereco" @blur="buscarCep" />
                </label>

            </div>
        </div>

        <div class="d-flex justify-content-end pt-4">

            <button class="btn btn-success" style="font-size: 2em;" @click="finalizarCompra">
                Confirmar!
            </button>
        </div>
    </div>
@endsection