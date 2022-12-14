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
        <div class="row">
            <h1>{{$user->firstName}} {{$user->lastName}}</h1>
        </div>

        <div class="row">

            <div class="col-4">
                @include('balance')
            </div>

            <div class="col-4">
                @include('BankAccount')
            </div>

        </div>

        <div class="row">

            <div class="d-flex flex-row align-itens-center justify-content-center mt-4">
                <div class="col-4">
                    @include('reportResume')
                </div>
            </div>
            
        </div>
    </main>

    @include('footer')

</body>

</html>