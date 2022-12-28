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

<div class="border shadow p-2 py-4 text-start">
    <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
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
                        <td>
                            @if($transaction->type == $transactionTypeLabel["cashout"])
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sign-turn-left-fill text-danger" viewBox="0 0 16 16">
                                    <path d="M9.05.435c-.58-.58-1.52-.58-2.1 0L.436 6.95c-.58.58-.58 1.519 0 2.098l6.516 6.516c.58.58 1.519.58 2.098 0l6.516-6.516c.58-.58.58-1.519 0-2.098L9.05.435ZM7 8.466a.25.25 0 0 1-.41.192L4.23 6.692a.25.25 0 0 1 0-.384l2.36-1.966a.25.25 0 0 1 .41.192V6h1.5A2.5 2.5 0 0 1 11 8.5V11h-1V8.5A1.5 1.5 0 0 0 8.5 7H7v1.466Z"/>
                                </svg>
                            @endif

                            @if($transaction->type == $transactionTypeLabel["cashin"])
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sign-turn-right-fill text-success" viewBox="0 0 16 16">
                                    <path d="M9.05.435c-.58-.58-1.52-.58-2.1 0L.436 6.95c-.58.58-.58 1.519 0 2.098l6.516 6.516c.58.58 1.519.58 2.098 0l6.516-6.516c.58-.58.58-1.519 0-2.098L9.05.435ZM9 8.466V7H7.5A1.5 1.5 0 0 0 6 8.5V11H5V8.5A2.5 2.5 0 0 1 7.5 6H9V4.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L9.41 8.658A.25.25 0 0 1 9 8.466Z"/>
                                </svg>
                            @endif
                            
                        </td>
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
                        <td class="text-center"><span class="badge bg-info d-flex flex-fill justify-content-center">R$@currence_cents($transaction->amount)</span></td>
                        <td class="text-center"><span class="badge bg-warning d-flex flex-fill justify-content-center">R$@currence_cents($transaction->tax_amount)</span></td>
                        <td>{{date('d/m/Y h:i', strtotime($transaction->created_at))}}</td>
                        <td class="text-center"><span class="badge bg-primary d-flex flex-fill justify-content-center">{{$transactionStatusLabel[$transaction->status]}}</span></td>
                    </tr>
                @endforeach
            @endisset
        </tbody>
    </table>
</div>
