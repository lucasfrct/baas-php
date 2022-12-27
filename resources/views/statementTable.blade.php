@isset($form['start_date'])
    <div class="row d-flex flex-row-reverse">
        <form action="{{route('bankStatement')}}" method="GET" class="border border-grey-200 rounded-3 row px-1 py-3 col-6 me-3 mb-3">
            <div class="d-flex flex-row-reverse col">
                <div class="input-group">
                    <span class="input-group-text">De:</span>
                    <input type="date" class="form-control" placeholder="Username" aria-label="Username" name="start_date" value={{$form['start_date']}}>
                    <span class="input-group-text">Até:</span>
                    <input type="date" class="form-control" placeholder="Server" aria-label="Server" name="end_date" value={{$form['end_date']}}>
                </div>
            </div>

            <div class="d-flex flex-row-reverse col-3">
                <button type="submit" class="btn btn-primary btn-sm px-5 py-0" style="height: 38px">Buscar</button>
            </div>
        </form>
    </div>
@endisset

<div class="border shadow p-2 py-4">
    <table class="table">
        <thead>
          <tr>
            <th scope="col">Pagador</th>
            <th scope="col">Recebedor</th>
            <th scope="col">Banco do recebedor</th>
            <th class="text-center" scope="col">Tranferencia</th>
            <th class="text-center" scope="col">Taxas</th>
            <th scope="col">Data</th>
            <th class="text-center" scope="col">Status</th>
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
                        <td class="text-center"><span class="badge bg-info">R$@currence_cents($transaction->amount)</span></td>
                        <td class="text-center"><span class="badge bg-warning">R$@currence_cents($transaction->tax_amount)</span></td>
                        <td>{{date('d/m/Y h:i', strtotime($transaction->created_at))}}</td>
                        <td class="text-center"><span class="badge bg-primary">{{$transactionTypeLabel[$transaction->status]}}</span></td>
                    </tr>
                @endforeach
            @endisset
        </tbody>
    </table>
</div>
