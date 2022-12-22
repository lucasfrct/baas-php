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

    <main class="row container-fluid p-0 m-0">

        <div class="col-4 border border-danger">
            <aside class="border border-info mt-5 mb-4">
                <h4 class="p-3">Lista de Bancos</h4>
                <ul class="list-group">
                    @isset($banksList)
                        @foreach ($banksList as $bank)
                            <li class="list-group-item" aria-current="true">{{$bank->company}}
                                <a href="http://localhost:8000/admin" target="_self">{{$bank->ispb}}</a>
                            </li>
                        @endforeach
                    @endisset
                </ul>
            </aside>
        </div>

        <div class="col-8 border border-danger">

        </div>

    </main>

    @include('footer')

</body>

</html>
