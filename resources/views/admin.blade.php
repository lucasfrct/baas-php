<!doctype html>
<html lang="pt-br">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="theme-color" content="#712cf9">

    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/bootstrap-5.2.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/bootstrap-icons-1.9.1.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/product.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/main.css') }}" />
    
    <script type="module" src="{{ URL::asset('js/bootstrap-5.1.min.js') }}" defer></script>
    <script type="module" src="{{ URL::asset('js/popper-2.11.min.js') }}" defer></script>
    
    <title>Baas PHP</title>

</head>

<body>

    @include('header')

    <main class="row container-fluid p-5 m-0">

        <div class="col-4 border-right-1">
            <aside class="mt-5 mb-4">
                <h4 class="p-3">Lista de Bancos</h4>
                <ul class="list-group">
                    @isset($banksList)
                        @foreach ($banksList as $bank)
                            <li class="list-group-item p-3" aria-current="true">
                                {{$bank->company}}<a class="cp-link" href="/admin?ispb={{$bank->ispb}}" target="_self"> {{$bank->ispb}}</a>
                            </li>
                        @endforeach
                    @endisset
                </ul>
            </aside>
        </div>

        <div class="col-8">
            <div class="row">
                <div class="col-4 p-2">
                    @isset($balance)
                        <h4 class="text-center text-success border border-success p-3 rounded">Saldo: R$ @currence_cents($balance)</h4>
                    @endisset
                </div>
                <div class="col-4 p-2">
                    @isset($prevBalance)
                        <h4 class="text-center text-info border border-info p-3 rounded">Saldo anterior: R$ @currence_cents($prevBalance)</h4>
                    @endisset
                </div>
                <div class="col-4 p-2">
                    @isset($total)
                        <h4 class="text-center text-primary border border-primary p-3 rounded">Transações: {{$total}}</h4>
                    @endisset
                </div>
                <div class="col-12 p-2 text-center">
                    <hr class="my-4">
                    @include('statementTable')
                </div>
            </div>            
        </div>
    </main>

    @include('footer')

</body>

</html>
