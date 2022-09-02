@isset($bankAccount)
  <div class="col-12 p-4 border rounded-3 shadow m-2">
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Banco</th>
          <th scope="col">Numero</th>
          <th scope="col">Operador</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row">{{$bankAccount["branch"]}}</th>
          <td>{{$bankAccount["number"]}}</td>
          <td>{{$bankAccount["operator"]}}</td>
        </tr>
      </tbody>
    </table>
  </div>
@endisset