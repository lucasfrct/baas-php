<!DOCTYPE html>
<html>
<head>
    <title>ContactMe</title>
 
    <!-- Latest compiled and minified CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"></head>
<body>
 
<div class="container">
 
    <h1>Cadastro de Usuario</h1>
 
    <hr />
 
    <form action="/user" method="POST" class="border border-grey-200 py-4 px-5 rounded-3 shadow m-4">
      
      <legend class="text-center h1">Novo Usuario</legend>

      <input type="hidden" name="_token" value="{{ csrf_token() }}">
 
      <div class="form-group mb-4">
        <label class="mb-2 ms-1" class="mb-2 ms-1" for="name">Name:</label>
        <input type="text" id="name" name="name" placeholder="name" value="Marcus" @class(['is-valid'=>$validations['success'], 'is-invalid'=>$validations['invalid'], 'form-control'])>
        @isset($validations['name'])
          <div class="feedback text-danger">{{ $validations['name'] }}</div>
        @endisset
      </div>
 
      <div class="form-group mb-4">
        <label class="mb-2 ms-1" for="email">E-Mail:</label>
        <input required type="email" id="email" name="email" class="form-control is-invalid" placeholder="E-Mail" value="abc@abc.com">
        @isset($validations['email'])
          <div class="feedback text-danger">{{ $validations['email'] }}</div>
        @endisset
      </div>

      <div class="form-group mb-4">
        <label class="mb-2 ms-1" for="cpf">CPF:</label>
        <input type="text" id="cpf" name="cpf" class="form-control is-invalid" placeholder="cpf" value="902.948.740-20">
        @isset($validations['cpf'])
          <div class="feedback text-danger">{{ $validations['cpf'] }}</div>
        @endisset
      </div>

      <div class="form-group mb-4">
        <label class="mb-2 ms-1" for="address">Endereço:</label>
        <input type="text" id="address" name="address" class="form-control is-invalid" placeholder="address" value="Rua joao da silva">
        @isset($validations['address'])
          <div class="feedback text-danger">{{ $validations['address'] }}</div>
        @endisset
      </div>

      <div class="form-group mb-4">
        <label class="mb-2 ms-1" for="fone">Telefone:</label>
        <input type="text" id="fone" name="fone" class="form-control is-invalid" placeholder="fone" value="22912345678">
        @isset($validations['fone'])
          <div class="feedback text-danger">{{ $validations['fone'] }}</div>
        @endisset
      </div>
 
      <div class="form-group mb-4">
        <label class="mb-2 ms-1" for="message">Mensagem:</label>
        <textarea rows="8" id="message" name="message" class="form-control is-invalid" placeholder="Digite sua mensagem">abcdfg</textarea>
        @isset($validations['message'])
          <div class="feedback text-danger">{{ $validations['message'] }}</div>
        @endisset
      </div>

      <div class="d-flex flex-row-reverse mt-4">
        <button type="submit" class="btn btn-primary btn-lg px-5">Enviar</button>
      </div>

      @isset($success)
        <div class="alert alert-success my-4" role="alert">Usuario criado com sucesso!</div>
      @endisset
 
    </form>
 
</div>
 
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
</html>