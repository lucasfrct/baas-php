<table class="table">
    <thead>
      <tr>
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
                    @isset($transaction->payerUser->firstName)
                        <td>{{$transaction->payerUser->firstName}} {{$transaction->payerUser->lastName}} {{$transaction->payer_document}}</td>
                    @endisset
                    @isset($transaction->payerUser->company)
                        <td>{{$transaction->payerUser->company}} {{$transaction->payer_document}}</td>
                    @endisset
                    @isset($transaction->receipientUser->firstName)
                        <td>{{$transaction->receipientUser->firstName}} {{$transaction->receipientUser->lastName}} {{$transaction->receipient_document}}</td>
                    @endisset
                    @isset($transaction->receipientUser->company)
                        <td>{{$transaction->receipientUser->company}} {{$transaction->receipient_document}}</td>
                    @endisset
                    <td>{{$transaction->receipient_bank_company}}</td>
                    <td><b>R$@currence_cents($transaction->amount)</b></td>
                    <td><b>R$@currence_cents($transaction->tax_amount)</b></td>
                    <td>{{date('d/m/Y h:i', strtotime($transaction->created_at))}}</td>
                </tr>
            @endforeach
        @endisset
    </tbody>
</table>