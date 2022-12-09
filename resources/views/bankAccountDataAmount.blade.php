<form action="{{route('transactionResume')}}" method="POST" class="border border-grey-200 py-4 px-5 rounded-3 shadow m-4"
style="width: 680px;">

    <div class="row">

        <legend class="text-center h4 mb-4">Valor a ser transferido</legend>

        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <input type="hidden" name="payer_uuid" value="{{ $transaction["payer_uuid"] }}">
        <input type="hidden" name="transaction_type" value="{{ $transaction["transaction_type"] }}">
        <input type="hidden" name="payer_bank_ispb" value="{{ $transaction["payer_bank_ispb"] }}">
        <input type="hidden" name="receipient_bank_ispb" value="{{ $transaction["receipient_bank_ispb"] }}">
        <input type="hidden" name="receipient_bank_branch" value="{{ $transaction["receipient_bank_branch"] }}">
        <input type="hidden" name="receipient_bank_number" value="{{ $transaction["receipient_bank_number"] }}">
        <input type="hidden" name="receipient_bank_operator" value="{{ $transaction["receipient_bank_operator"] }}">

        <div class="mb-4 col-12">
            <div class="form-group">
                <label class="mb-2 ms-1" class="mb-2 ms-1" for="operator_type">Tipo de conta:</label>
                <select class="form-select" id="operator_type" name="operator_type"
                    @class([
                        'is-valid' => !$errors->has('operator_type'),
                        'is-invalid' => $errors->has('operator_type'),
                        'form-control',
                    ])
                >
                    <option selected disabled>Selecione o banco para transferir:</option>
                    @isset($operatorTypes)
                        @foreach ($operatorTypes as $type)
                            <option value="{{$type->value}}">{{ $type->name }}</option>
                        @endforeach
                    @endisset
                    
                </select>


            </div>
            @error('operator_type')
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

        <div class="mb-2 col-12">
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
        </div>

        <div class="mb-4 col-12 p-3">
            <div class="alert alert-primary border border-info border-dashed" role="alert">
                <span>Transacao para: <b class="text-uppercase">{{$receipientData->firstName}}</b></span>
            </div>
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