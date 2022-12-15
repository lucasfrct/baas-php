<table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Pagador</th>
        <th scope="col">Recebedor</th>
        <th scope="col">Banco do recebedor</th>
        <th scope="col">Valor da tranferencia</th>
        <th scope="col">Valor das taxas</th>
        <th scope="col">Data</th>
      </tr>
    </thead>
    <tbody>
        @isset($transactionsList)
            @foreach ($transactionsList as $transaction)
                <tr>
                    <th scope="row">1</th>
                    <td>{{$transaction->payer_document}}</td>
                    <td>{{$transaction->receipient_document}}</td>
                    <td>{{$transaction->receipient_bank_company}}</td>
                    <td>{{$transaction->amount}}</td>
                    <td>{{$transaction->tax_amount}}</td>
                    <td>{{$transaction->created_at}}</td>
                </tr>
            @endforeach
        @endisset
    </tbody>
</table>