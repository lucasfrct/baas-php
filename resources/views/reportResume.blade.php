<div class="card">
    <h5 class="card-header">Resumo da transacao</h5>
    <div class="card-body">

        <div class="row p-4">
            <h4>Dados do Pagador</h4>
            <div class="col-12">
                <span>Nome: {{$user->firstName}} {{$user->lastName}}</span>
            </div>
            <div class="col-6">
                <span>Banco: {{$userBank->company}}</span>
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
            <div class="col-12">
                <span>Valor da Transferencia: {{$transaction["amount"]}}</span>
            </div>
            <div class="col-6">
                <span>Tipo da Transferencia: {{$transaction["transaction_type"]}}</span>
            </div>
        </div>

        <div class="row p-4">
            <h4>Dados do Recebedor</h4>
            <div class="col-12">
                <span>Nome: {{$receipientData->firstName}} {{$receipientData->lastName}}</span>
            </div>
            <div class="col-6">
                <span>Banco: {{$receipientBank->company}}</span>
            </div>
            <div class="col-6">
                <span>Agencia: {{$receipientBankAccount->branch}}</span>
            </div>
            <div class="col-6">
                <span>Conta Corrente (cc): {{$receipientBankAccount->number}}</span>
            </div>
            <div class="col-6">
                <span>Operador: {{$receipientBankAccount->operator}}</span>
            </div>
        </div>

        <div class="row p-4">
            <h4>Taxas Bancarias</h4>
            <div class="col-12">
                <span>Valor: {{$packagesAmount}}</span>
            </div>
            <div class="col-6">
                <span>Data: {{$dateTime}}</span>
            </div>
        </div>

        <div class="row p-4">
            <h4>Transferencia</h4>
            <div class="col-12">
                <span>Valor da transacao (Transferencia + taxas): {{$amountCharge}}</span>
            </div>
            <div class="col-6">
                <span>Data: {{$dateTime}}</span>
            </div>
        </div>

        <form class="row p-4" action="{{route('transactionResume')}}" method="POST">
            <input type="hidden" name="payer_uuid" value="{{ $transaction["payer_uuid"] }}">
            <input type="hidden" name="transaction_type" value="{{ $transaction["transaction_type"] }}">
            <input type="hidden" name="payer_bank_ispb" value="{{ $transaction["payer_bank_ispb"] }}">
            <input type="hidden" name="receipient_bank_ispb" value="{{ $transaction["receipient_bank_ispb"] }}">
            <input type="hidden" name="receipient_bank_branch" value="{{ $transaction["receipient_bank_branch"] }}">
            <input type="hidden" name="receipient_bank_number" value="{{ $transaction["receipient_bank_number"] }}">
            <input type="hidden" name="receipient_bank_operator" value="{{ $transaction["receipient_bank_operator"] }}">
            <input type="hidden" name="payer_operator_type" value="{{ $transaction["payer_operator_type"] }}">
            <input type="hidden" name="amount" value="{{ $transaction["amount"] }}">

            <div class="d-flex flex-row-reverse mt-4">
                <button type="submit" class="btn btn-primary btn-lg px-5">Continuar</button>
            </div>
        </form>

    </div>
</div>