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

    <title>Home</title>

</head>

<body>
    @include('header')

    <main class="container-fluid my-2 row" style="min-height: 100vh">

        <div class="d-flex align-itens-center justify-content-center border border-danger">

            <form action="/" method="POST" class="border border-grey-200 py-4 px-5 rounded-3 shadow m-4"
                style="width: 680px;">

                <div class="row">

                    <legend class="text-center h1 mb-4">Criar Conta</legend>

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="form-group mb-4 col-6">
                        <label class="mb-2 ms-1" class="mb-2 ms-1" for="firstName">Primeiro Nome:</label>
                        <input type="text" id="firstName" name="firstName" placeholder="Primeiro Nome" value="Marcus"
                            @class([
                                'is-valid' => $validations['success'],
                                'is-invalid' => $validations['invalid'],
                                'form-control',
                            ])>
                        @isset($validations['firstName'])
                            <div class="feedback text-danger">{{ $validations['firstName'] }}</div>
                        @endisset
                    </div>

                    <div class="form-group mb-4 col-6">
                        <label class="mb-2 ms-1" class="mb-2 ms-1" for="lastName">Ultimo Nome:</label>
                        <input type="text" id="lastName" name="lastName" placeholder="Ultimo Nome" value="Mariano"
                            @class([
                                'is-valid' => $validations['success'],
                                'is-invalid' => $validations['invalid'],
                                'form-control',
                            ])>
                        @isset($validations['lastName'])
                            <div class="feedback text-danger">{{ $validations['lastName'] }}</div>
                        @endisset
                    </div>

                    <div class="form-group mb-4  col-12">
                        <label class="mb-2 ms-1" for="email">E-Mail:</label>
                        <input required type="email" id="email" name="email" placeholder="E-Mail" value="abc@abc.com"                        
                            @class([
                                'is-valid' => $validations['success'],
                                'is-invalid' => $validations['invalid'],
                                'form-control',
                            ])>
                        @isset($validations['email'])
                            <div class="feedback text-danger">{{ $validations['email'] }}</div>
                        @endisset
                    </div>

                    <div class="form-group mb-4 col-6">
                        <label class="mb-2 ms-1" for="cpf">CPF:</label>
                        <input type="text" id="cpf" name="cpf" placeholder="cpf" value="902.948.740-20"                        
                            @class([
                                'is-valid' => $validations['success'],
                                'is-invalid' => $validations['invalid'],
                                'form-control',
                            ])>
                        @isset($validations['cpf'])
                            <div class="feedback text-danger">{{ $validations['cpf'] }}</div>
                        @endisset
                    </div>

                    <div class="form-group mb-4 col-6">
                        <label class="mb-2 ms-1" for="fone">Telefone:</label>
                        <input type="text" id="fone" name="fone" placeholder="fone" value="22912345678"
                            @class([
                                'is-valid' => $validations['success'],
                                'is-invalid' => $validations['invalid'],
                                'form-control',
                            ])>
                        @isset($validations['fone'])
                            <div class="feedback text-danger">{{ $validations['fone'] }}</div>
                        @endisset
                    </div>

                    <div class="d-flex flex-row-reverse mt-4">
                        <button type="submit" class="btn btn-primary btn-lg px-5">Cadastrar</button>
                    </div>

                    @isset($success)
                        <div class="alert alert-success my-4" role="alert">Usuario criado com sucesso!</div>
                    @endisset
                </div>


            </form>

        </div>


        <div class="d-md-flex flex-md-equal w-100 my-md-3 ps-md-3">
            <div class="bg-light me-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
                <div class="my-3 p-3">
                    <h2 class="display-5">Another headline</h2>
                    <p class="lead">And an even wittier subheading.</p>
                </div>
                <div class="bg-body shadow-sm mx-auto" style="width: 80%; height: 300px; border-radius: 21px 21px 0 0;">
                </div>
            </div>
            <div class="bg-light me-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
                <div class="my-3 py-3">
                    <h2 class="display-5">Another headline</h2>
                    <p class="lead">And an even wittier subheading.</p>
                </div>
                <div class="bg-body shadow-sm mx-auto" style="width: 80%; height: 300px; border-radius: 21px 21px 0 0;">
                </div>
            </div>
        </div>

    </main>

    @include('footer')

</body>
