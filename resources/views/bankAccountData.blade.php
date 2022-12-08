<form action="{{route('transactionCheck')}}" method="POST" class="border border-grey-200 py-4 px-5 rounded-3 shadow m-4"
style="width: 680px;">

    <div class="row">

        <legend class="text-center h4 mb-4">Dados do recebedor</legend>

        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <input type="hidden" name="payer_uuid" value="{{ $user->uuid }}">
        <input type="hidden" name="transaction_type" value="{{ $cashout }}">
        <input type="hidden" name="payer_bank_ispb" value="{{ $userBank->ispb }}">

        <div class="mb-4 col-12">
            <div class="form-group">
                <label class="mb-2 ms-1" class="mb-2 ms-1" for="receipient_bank_ispb">Banco:</label>
                <select class="form-select" id="receipient_bank_ispb" name="receipient_bank_ispb" value="Jumeci Bank"
                    @class([
                        'is-valid' => !$errors->has('receipient_bank_ispb'),
                        'is-invalid' => $errors->has('receipient_bank_ispb'),
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
            @error('receipient_bank_ispb')
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
                <label class="mb-2 ms-1" class="mb-2 ms-1" for="receipient_bank_branch">Agencia:</label>
                <input type="text" id="receipient_bank_branch" name="receipient_bank_branch" placeholder="Agencia" value="001"
                    @class([
                        'is-valid' => !$errors->has('receipient_bank_branch'),
                        'is-invalid' => $errors->has('receipient_bank_branch'),
                        'form-control',
                    ])
                >
            </div>
            @error('receipient_bank_branch')
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
                <label class="mb-2 ms-1" for="receipient_bank_number">Conta Corrente (cc):</label>
                <input type="text" id="receipient_bank_number" name="receipient_bank_number" placeholder="Conta Corrente (cc)" value="000001"
                    @class([
                        'is-valid' => !$errors->has('receipient_bank_number'),
                        'is-invalid' => $errors->has('receipient_bank_number'),
                        'form-control',
                    ])
                >
            </div>
            @error('receipient_bank_number')
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
                <label class="mb-2 ms-1" for="receipient_bank_operator">Operador:</label>
                <input type="number" id="receipient_bank_operator" name="receipient_bank_operator" placeholder="Operador" value="1" min="0" max="2"
                    @class([
                        'is-valid' => !$errors->has('receipient_bank_operator'),
                        'is-invalid' => $errors->has('receipient_bank_operator'),
                        'form-control',
                    ])
                >
            </div>
            @error('receipient_bank_operator')
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

        @error('errorDefault')
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

</form>