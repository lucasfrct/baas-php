{{-- <div class="row">
 
    <form action="/bank-account" method="POST" class="border border-grey-200 p-3 rounded-3 shadow m-2 row">
      
      <legend class="text-center h4">Nova Conta</legend>

      <input type="hidden" name="_token" value="{{ csrf_token() }}">

      <div class="form-group mb-4">
        <label class="mb-2 ms-1" for="branch">Instituição bancaria:</label>
        <select id="branch" name="branch" class="form-control">
            <option>Itaú</option>
            <option>Caixa Economica</option>
            <option>Bradesco</option>
            <option>Banco do Brasil</option>

        </select>
      </div>
 
      <div class="form-group mb-4 col-9">
        <label class="mb-2 ms-1" class="mb-2 ms-1" for="number">Numero da conta:</label>
        <input type="text" id="number" name="number" placeholder="Numero da conta" value="00001" @class('form-control')>
      </div>
 
      <div class="form-group mb-4 col-3">
        <label class="mb-2 ms-1" for="operator">Operador:</label>
        <input required type="operator" id="operator" name="operator" class="form-control" placeholder="Operador" value="1">
      </div>      

      <div class="d-flex flex-row-reverse mt-4">
        <button type="submit" class="btn btn-primary btn-lg px-5">Enviar</button>
      </div>
 
    </form>

    @isset($uuid)
      <div class="row p-5">
        <a href="/bank-account/{{$uuid}}" class="btn btn-primary col-4">Mostrar</a>
      </div>
    @endisset
 
</div> --}}

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