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

            <form action="/signin" method="POST" class="border border-grey-200 py-4 px-5 rounded-3 shadow m-4" style="min-width: 560px;">
    
                <legend class="text-center h1">Novo Usuario</legend>
    
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
    
                <div class="form-group mb-4">
                    <label class="mb-2 ms-1" class="mb-2 ms-1" for="name">Name:</label>
                    <input type="text" id="name" name="name" placeholder="name" value="Marcus" 
                        @class([
                        'is-valid' => $validations['success'],
                        'is-invalid' => $validations['invalid'],
                        'form-control',
                        ])
                    >
                    @isset($validations['name'])
                        <div class="feedback text-danger">{{ $validations['name'] }}</div>
                    @endisset
                </div>
    
                <div class="form-group mb-4">
                    <label class="mb-2 ms-1" for="email">E-Mail:</label>
                    <input required type="email" id="email" name="email" class="form-control is-invalid"
                        placeholder="E-Mail" value="abc@abc.com">
                    @isset($validations['email'])
                        <div class="feedback text-danger">{{ $validations['email'] }}</div>
                    @endisset
                </div>
    
                <div class="form-group mb-4">
                    <label class="mb-2 ms-1" for="cpf">CPF:</label>
                    <input type="text" id="cpf" name="cpf" class="form-control is-invalid" placeholder="cpf"
                        value="902.948.740-20">
                    @isset($validations['cpf'])
                        <div class="feedback text-danger">{{ $validations['cpf'] }}</div>
                    @endisset
                </div>
    
                {{-- <div class="form-group mb-4">
                    <label class="mb-2 ms-1" for="address">Endereço:</label>
                    <input type="text" id="address" name="address" class="form-control is-invalid"
                        placeholder="address" value="Rua joao da silva">
                    @isset($validations['address'])
                        <div class="feedback text-danger">{{ $validations['address'] }}</div>
                    @endisset
                </div> --}}
    
                <div class="form-group mb-4">
                    <label class="mb-2 ms-1" for="fone">Telefone:</label>
                    <input type="text" id="fone" name="fone" class="form-control is-invalid" placeholder="fone"
                        value="22912345678">
                    @isset($validations['fone'])
                        <div class="feedback text-danger">{{ $validations['fone'] }}</div>
                    @endisset
                </div>
    
                {{-- <div class="form-group mb-4">
                    <label class="mb-2 ms-1" for="message">Mensagem:</label>
                    <textarea rows="8" id="message" name="message" class="form-control is-invalid"
                        placeholder="Digite sua mensagem">abcdfg</textarea>
                    @isset($validations['message'])
                        <div class="feedback text-danger">{{ $validations['message'] }}</div>
                    @endisset
                </div> --}}
    
                <div class="d-flex flex-row-reverse mt-4">
                    <button type="submit" class="btn btn-primary btn-lg px-5">Enviar</button>
                </div>
    
                @isset($success)
                    <div class="alert alert-success my-4" role="alert">Usuario criado com sucesso!</div>
                @endisset
    
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
                <div class="bg-body shadow-sm mx-auto"
                    style="width: 80%; height: 300px; border-radius: 21px 21px 0 0;">
                </div>
            </div>
        </div>

    </main>

    @include('footer')

</body>
