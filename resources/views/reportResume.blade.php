<div class="card">
    <h5 class="card-header">Resumo da transacao</h5>
    <div class="card-body">

        <div class="row p-4">
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

        <div class="row p-4">
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

        <div class="row p-4">
            <div class="col-12">
                <span>Valor: {{$bank->company}}</span>
            </div>
            <div class="col-6">
                <span>Tipo: {{$bankAccount->branch}}</span>
            </div>
            <div class="col-6">
                <span>Data: {{$bankAccount->number}}</span>
            </div>
        </div>

    </div>
</div>