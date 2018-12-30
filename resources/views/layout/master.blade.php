<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Carrinho Mobly</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<main>
    <header id="app-header">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <a class="navbar-brand" href="/">Carrinho Mobly</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarColor01">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link p-0 pl-2" href="{{ route('carrinho') }}">
                            <button class="btn bg-white text-primary">
                                Carrinho
                                <i class="fas fa-shopping-cart text-primary ml-1"></i>
                            </button>
                        </a>
                    </li>
                </ul>
                <div class="col-auto">
                    <div class="btn-group" role="group" aria-label="Basic example" v-if="!usuario">
                        <a href="{{ route('cadastrar') }}" class="btn bg-white text-primary">
                            Cadastrar
                        </a>
                        <a href="{{ route('entrar') }}" class="btn border-white text-white">
                            Entrar
                        </a>
                    </div>
                    <div class="btn-group" role="group" aria-label="Basic example" v-if="usuario">
                        <a href="#" @click="logout" class="btn bg-white text-primary">
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </nav>
        @yield('header')
    </header>
    <section>
        @yield('content')
    </section>
</main>

<footer class="page-footer font-small bg-primary text-white pt-5 mt-5">

    <!-- Footer Links -->
    <div class="container-fluid text-center text-md-left">

        <!-- Grid row -->
        <div class="row">

            <!-- Grid column -->
            <div class="col-md-6 mt-md-0 mt-3">

                <!-- Content -->
                <h5 class="text-uppercase text-white-50">Victor Rodrigues</h5>
                <p class="pr-5">
                    Programador Web Full-Stack, na área desde 2011 ao ingressar na Fatec.
                    Desde, então atuou em diversos projetos e frentes, desenvolvedo ferramentas, Dashboards, sites e LPs.
                </p>

            </div>
            <!-- Grid column -->

            <hr class="clearfix w-100 d-md-none pb-3">


            <div class="col-md-3 mb-md-0 mb-3">

                <!-- Links -->
                <h5 class="text-uppercase text-white-50">Outros Projetos</h5>

                <ul class="list-unstyled">
                    <li>
                        <a href="#!">CrediNestle</a>
                    </li>
                    <li>
                        <a href="#!">American Express</a>
                    </li>
                    <li>
                        <a href="#!">Stan</a>
                    </li>
                </ul>

            </div>

            <div class="col-md-3 mb-md-0 mb-3">

                <!-- Links -->
                <h5 class="text-uppercase  text-white-50">Social</h5>

                <ul class="list-unstyled">
                    <li>
                        <a href="#!">github</a>
                    </li>
                    <li>
                        <a href="#!">facebook</a>
                    </li>
                    <li>
                        <a href="#!">linkedin</a>
                    </li>
                </ul>

            </div>

        </div>
        <!-- Grid row -->

    </div>
    <!-- Footer Links -->

    <!-- Copyright -->
    <div class="footer-copyright text-center py-3 mt-4 bg-dark"  style="filter:brightness(75%);">© 2018 Copyright:
        <a href="https://mdbootstrap.com/education/bootstrap/"> vituhugo</a>
    </div>
    <!-- Copyright -->

</footer>

<link rel="stylesheet" type="text/css" href="{{asset('css/app.css')}}">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
