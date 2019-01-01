@extends('layout.master')
@section('content')
    <section class="container pt-5" id="app-cadastro">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">
                    Formulário de Cadastro
                </h3>
            </div>
            <form id="frm-cadastro" class="card-body row" @submit="onSubmit">
                <label class="form-group col-12">
                    <span>Nome completo</span>
                    <input v-model="input.nome" class="form-control" placeholder="Fulano de tal da Silva" required>
                </label>


                <label class="form-group col-12">
                    <span>Email</span>
                    <input type="email" v-model="input.email" class="form-control" placeholder="example@example.com" required>
                </label>


                <label class="form-group col-12">
                    <span>Senha</span>
                    <input type="password" v-model="input.senha" class="form-control" placeholder="Mínimo 6 digitos" required>
                </label>

                <h3 class="card-title col-12 mt-5 mb-0">
                    Endereço
                </h3>
                <hr style="width: 100%">

                <label class="form-group col-12">
                    <span>CEP</span>
                    <input type="text" v-model="input.cep" class="form-control" placeholder="00000-000" @blur="buscarCep" required>
                </label>

                <label class="form-group col-10">
                    <span>Endereço</span>
                    <input type="text" v-model="input.endereco" class="form-control" placeholder="Av. Exemplo, 99" readonly required>
                </label>

                <label class="form-group col-2">
                    <span>Número</span>
                    <input type="text" v-model="input.numero" class="form-control" placeholder="999" required>
                </label>

                <label class="form-group col-12">
                    <span>Telefone <small>Com DDD</small></span>
                    <input type="text" v-model="input.telefone" class="form-control" placeholder="(99) 9 9999 9999" required>
                </label>
            </form>

            <footer class="card-footer">
                <div class="col-12 text-right">
                    <button class="btn border-secondary mr-2" onclick="history.back()">Voltar</button>
                    <button class="btn btn-secondary" type="submit" form="frm-cadastro">Cadastrar</button>
                </div>
            </footer>

        </div>
    </section>
@endsection