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