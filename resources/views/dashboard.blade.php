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
                <div class="card">
                    <h5 class="card-header">Saldo</h5>
                    <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            @isset($bankAccount)
                                <h2>R$ @currence_cents($bankAccount->balance)</h2>
                            @endisset
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            <div class="col-4">
                <div class="card">
                    <h5 class="card-header">Conta Bancaria</h5>
                    <div class="card-body">
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
                    </div>
                </div>
            </div>

        </div>

        <div class="row">

            <div class="d-flex align-itens-center justify-content-center">

                <form action="{{route('applyingTransaction')}}" method="POST" class="border border-grey-200 py-4 px-5 rounded-3 shadow m-4"
                    style="width: 680px;">

                    <div class="row">

                        <legend class="text-center h4 mb-4">Dados do recebedor</legend>

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <input type="hidden" name="payer_uuid" value="{{ $user->uuid }}">
                        <input type="hidden" name="transaction_type" value="cashout">
                        <input type="hidden" name="payer_bank_ispb" value="{{ $bank->ispb }}">

                        {{-- <div class="mb-4 col-4">
                            <div class="form-group">
                                <label class="mb-2 ms-1" for="amount">Valor da transferencia:</label>
                                <input type="text" id="amount" name="amount" placeholder="Valor da transferencia:" value="0,00"
                                    @class([
                                        'is-valid' => !$errors->has('amount'),
                                        'is-invalid' => $errors->has('amount'),
                                        'form-control',
                                    ])>
                            </div>
                            @error('amount')
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
                        </div> --}}

                        <div class="mb-4 col-12">
                            <div class="form-group">
                                <label class="mb-2 ms-1" class="mb-2 ms-1" for="bank">Banco:</label>
                                <select class="form-select" id="bank" name="receipient_bank_ispb" value="Jumeci Bank"
                                    @class([
                                        'is-valid' => !$errors->has('bank'),
                                        'is-invalid' => $errors->has('bank'),
                                        'form-control',
                                    ])
                                >
                                    <option selected disabled>Selecione o banco para transferir:</option>
                                    @isset($banksList)
                                        @foreach ($banksList as $bank)
                                            <option value="{{$bank->ispb}}">{{ $bank->company }}</option>
                                        @endforeach
                                    @endisset
                                    
                                </select>


                            </div>
                            @error('bank')
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
                        
                        <div class="mb-4 col-4">
                            <div class="form-group">
                                <label class="mb-2 ms-1" class="mb-2 ms-1" for="branch">Agencia:</label>
                                <input type="text" id="branch" name="receipient_bank_branch" placeholder="Agencia" value="001"
                                    @class([
                                        'is-valid' => !$errors->has('branch'),
                                        'is-invalid' => $errors->has('branch'),
                                        'form-control',
                                    ])>
                            </div>
                            @error('branch')
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

                        <div class="mb-4 col-4">
                            <div class="form-group">
                                <label class="mb-2 ms-1" for="number">Conta Corrente (cc):</label>
                                <input type="text" id="number" name="receipient_bank_number" placeholder="Conta Corrente (cc)" value="000001"
                                    @class([
                                        'is-valid' => !$errors->has('number'),
                                        'is-invalid' => $errors->has('number'),
                                        'form-control',
                                    ])>
                            </div>
                            @error('number')
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

                        <div class="mb-4 col-4">
                            <div class="form-group">
                                <label class="mb-2 ms-1" for="operator">Operador:</label>
                                <input type="number" id="operator" name="receipient_bank_operator" placeholder="Operador" value="1" min="0" max="2"
                                    @class([
                                        'is-valid' => !$errors->has('operator'),
                                        'is-invalid' => $errors->has('operator'),
                                        'form-control',
                                    ])>
                            </div>
                            @error('operator')
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
                            <button type="submit" class="btn btn-primary btn-lg px-5">Continuar</button>
                        </div>

                        @isset($success)
                            <div class="alert alert-success my-4" role="alert">Beneficiário enviado com sucesso!</div>
                        @endisset
                    </div>

                </form>

            </div>

        </div>
    </main>

    @include('footer')

</body>

</html>