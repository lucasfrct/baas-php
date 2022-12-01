<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="theme-color" content="#712cf9">

    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/bootstrap-5.2.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/product.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/main.css') }}" />

    <script src="{{ URL::asset('js/bootstrap-5.1.min.js') }}" defer></script>
    <script src="{{ URL::asset('js/popper-2.11.min.js') }}" defer></script>

    <title>Dashboard</title>

</head>

<body>
    @include('header')

    <main class="container-fluid my-2" style="min-height: 100vh">

    <h1> Marcus Feio</h1>

    <div class="card">
        <h5 class="card-header">{{$user->firstName}} {{$user->lastName}}</h5>
        <div class="card-body">
          <h5 class="card-title">Conta Bancaria</h5>
          <div class="row">
            <div class="col-6">
                <span>Banco: {{$bank->company}}</span>
            </div>
            <div class="col-6">
                <span>Agencia: {{$bankAccount->branch}}</span>
            </div>
            <div class="col-6">
                <span>Conta Corrente (cc): {{$bankAccount->number}}</span>
            </div>
            <div class="col-6">
                <span>Operador: {{$bankAccount->operator}}</span>
            </div>
          </div>
          <a href="#" class="btn btn-primary">Go somewhere</a>
        </div>
    </div>

    </main>

    @include('footer')

</body>

</html>