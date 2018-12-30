@extends('layout.master')
@section('content')
    <section class="container pt-5" id="app-entrar">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">
                    Formulário de Login
                </h3>
            </div>
            <form id="frm-entrar" class="card-body row" @submit="onSubmit">
                <label class="form-group col-12">
                    <span>Email</span>
                    <input type="email" v-model="input.email" class="form-control" placeholder="example@example.com" required>
                </label>

                <label class="form-group col-12">
                    <span>Senha</span>
                    <input type="password" v-model="input.senha" class="form-control" placeholder="Mínimo 6 digitos"  required>
                </label>
            </form>

            <footer class="card-footer">
                <div class="col-12 text-right">
                    <button class="btn border-secondary mr-2" onclick="history.back()">Voltar</button>
                    <button class="btn btn-secondary" type="submit" form="frm-entrar">Entrar</button>
                </div>
            </footer>

        </div>
    </section>
@endsection