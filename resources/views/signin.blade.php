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

    <main class="container-fluid my-2" style="min-height: 100vh">

        <div class="d-flex align-itens-center justify-content-center">

            <form action="{{route('signin')}}" method="POST" class="border border-grey-200 py-4 px-5 rounded-3 shadow m-4"
                style="width: 680px;">

                <div class="row">

                    <legend class="text-center h1 mb-4">Criar Conta</legend>

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="mb-4 col-6">
                        <div class="form-group">
                            <label class="mb-2 ms-1" class="mb-2 ms-1" for="firstName">Primeiro Nome:</label>
                            <input type="text" id="firstName" name="firstName" placeholder="Primeiro Nome" value="Marcus"
                                @class([
                                    'is-valid' => !$errors->has('firstName'),
                                    'is-invalid' => $errors->has('firstName'),
                                    'form-control',
                                ])>
                        </div>
                        @error('firstName')
                            <div class="feedback text-danger">
                                <span class="ms-2 me-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                        fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
                                        <path
                                            d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z" />
                                        <path
                                            d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z" />
                                    </svg>
                                </span>
                                <span style="font-size: 11px;">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>
                    
                    <div class="mb-4 col-6">
                        <div class="form-group">
                            <label class="mb-2 ms-1" class="mb-2 ms-1" for="lastName">Ultimo Nome:</label>
                            <input type="text" id="lastName" name="lastName" placeholder="Ultimo Nome" value="Mariano"
                                @class([
                                    'is-valid' => !$errors->has('lastName'),
                                    'is-invalid' => $errors->has('lastName'),
                                    'form-control',
                                ])>
                        </div>
                        @error('lastName')
                            <div class="feedback text-danger">
                                <span class="ms-2 me-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                        fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
                                        <path
                                            d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z" />
                                        <path
                                            d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z" />
                                    </svg>
                                </span>
                                <span style="font-size: 11px;">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4 col-12">
                        <div class="form-group">
                            <label class="mb-2 ms-1" for="email">E-Mail:</label>
                            <input type="email" id="email" name="email" placeholder="E-Mail" value="abc@abc.com" 
                                @class([
                                    'is-valid' => !$errors->has('email'),
                                    'is-invalid' => $errors->has('email'),
                                    'form-control',
                                ])>
                        </div>
                        @error('email')
                            <div class="feedback text-danger">
                                <span class="ms-2 me-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                        fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
                                        <path
                                            d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z" />
                                        <path
                                            d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z" />
                                    </svg>
                                </span>
                                <span style="font-size: 11px;">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4 col-6">
                        <div class="form-group">
                            <label class="mb-2 ms-1" for="cpf">CPF:</label>
                            <input type="text" id="cpf" name="cpf" placeholder="cpf" value="90294874020"
                                @class([
                                    'is-valid' => !$errors->has('cpf'),
                                    'is-invalid' => $errors->has('cpf'),
                                    'form-control',
                                ])>
                        </div>
                        @error('cpf')
                            <div class="feedback text-danger">
                                <span class="ms-2 me-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                        fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
                                        <path
                                            d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z" />
                                        <path
                                            d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z" />
                                    </svg>
                                </span>
                                <span style="font-size: 11px;">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4 col-6">
                        <div class="form-group">
                            <label class="mb-2 ms-1" for="fone">Telefone:</label>
                            <input type="text" id="fone" name="fone" placeholder="fone" value="22912345678"
                                @class([
                                    'is-valid' => !$errors->has('fone'),
                                    'is-invalid' => $errors->has('fone'),
                                    'form-control',
                                ])>
                        </div>
                        @error('fone')
                            <div class="feedback text-danger">
                                <span class="ms-2 me-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                        fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
                                        <path
                                            d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z" />
                                        <path
                                            d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z" />
                                    </svg>
                                </span>
                                <span style="font-size: 11px;">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4 col-6">
                        <div class="form-group">
                            <label class="mb-2 ms-1" class="mb-2 ms-1" for="password">Senha:</label>
                            <input type="password" id="password" name="password" placeholder="Senha" value="Alterar@123"
                                @class([
                                    'is-valid' => !$errors->has('password'),
                                    'is-invalid' => $errors->has('password'),
                                    'form-control',
                                ])>
                        </div>
                        @error('password')
                            <div class="feedback text-danger">
                                <span class="ms-2 me-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                        fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
                                        <path
                                            d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z" />
                                        <path
                                            d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z" />
                                    </svg>
                                </span>
                                <span style="font-size: 11px;">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4 col-6">
                        <div class="form-group">
                            <label class="mb-2 ms-1" class="mb-2 ms-1" for="confirm_password">Confirme a senha:</label>
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirme Senha" value="Alterar@123"
                                @class([
                                    'is-valid' => !$errors->has('confirm_password'),
                                    'is-invalid' => $errors->has('confirm_password'),
                                    'form-control',
                                ])>
                        </div>
                        @error('confirm_password')
                            <div class="feedback text-danger">
                                <span class="ms-2 me-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                        fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
                                        <path
                                            d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z" />
                                        <path
                                            d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z" />
                                    </svg>
                                </span>
                                <span style="font-size: 11px;">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="d-flex flex-row-reverse mt-4">
                        <button type="submit" class="btn btn-primary btn-lg px-5">Cadastrar</button>
                    </div>

                    @isset($success)
                        <div class="alert alert-success my-4" role="alert">Usuario criado com sucesso!</div>
                    @endisset
                </div>

                <div class="col-12 mt-4">
                    <a href="/login">Entrar numa conta já existente</a>
                </div>

            </form>

        </div>


        <div class="d-md-flex flex-md-equal w-100 my-md-3 ps-md-3">
            <div class="bg-light me-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
                <div class="my-3 p-3">
                    <h2 class="display-5">Another headline</h2>
                    <p class="lead">And an even wittier subheading.</p>
                </div>
                <div class="bg-body shadow-sm mx-auto"
                    style="width: 80%; height: 300px; border-radius: 21px 21px 0 0;">
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

</html>
