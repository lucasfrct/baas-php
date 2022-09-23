<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Emarref\Jwt\Token;

use Illuminate\Support\Facades\Auth;

use Ramsey\Uuid\Uuid;

class UserController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    /**
     * retorn uma formuláriop de SIGNIN
     */
    public function signinCreate(){
        $validations = [];
        $validations["invalid"] = false;
        $validations["success"] = false;

        return view("signin", ["validations"=> $validations]);
    }
    
    /**
     * Amazena um novo usuário do formulário SIGNIN
     * @params {Array}: [ firsName, lastName, email, cpf, telefone senha, confir_senha ]
     * @returs: retorn um formulário de signin
     */
    public function signinStore(Request $request){
        $form = $request->all();
        
        $validations = [];
        $validations["invalid"] = false;
        $validations["success"] = false;

        $firstName = $form["firstName"] ?? "";
        if (empty($firstName)) {
            $validations["firstName"] = "campo do primeiro nome não pode ser vazio";
            $validations["invalid"] = true;
            $validations["success"] = false;
            return view("signin", ["validations"=> $validations]);
        };

        $lastName = $form["lastName"] ?? "";
        if (empty($lastName)) {
            $validations["lastName"] = "campo do ultimo nome não pode ser vazio";
            $validations["invalid"] = true;
            $validations["success"] = false;
            return view("signin", ["validations"=> $validations]);
        };

        $email = $form["email"] ?? "";
        if (empty($email)) {
            $validations["email"] = "campo email não pode ser vazio";
            $validations["invalid"] = true;
            $validations["success"] = false;
            return view("signin", ["validations"=> $validations]);
        };

        $cpf = $form["cpf"] ?? "";
        if (empty($cpf)) {
            $validations["cpf"] = "campo cpf não pode ser vazio";
            $validations["invalid"] = true;
            $validations["success"] = false;
            return view("signin", ["validations"=> $validations]);
        };

        $fone = $form["fone"] ?? "";// operador de coalescência (??) / null coalesce
        if (empty($fone)) {
            $validations["fone"] = "campo telefone não pode ser vazio";
            $validations["invalid"] = true;
            $validations["success"] = false;
            return view("signin", ["validations"=> $validations]);
        };

        $password = $form["password"] ?? "";// operador de coalescência (??) / null coalesce
        if (empty($password)) {
            $validations["password"] = "campo senha não pode ser vazio";
            $validations["invalid"] = true;
            $validations["success"] = false;
            return view("signin", ["validations"=> $validations]);
        };

        $confirm_password = $form["confirm_password"] ?? "";// operador de coalescência (??) / null coalesce
        if (empty($confirm_password)) {
            $validations["confirm_password"] = "campo senha não pode ser vazio";
            $validations["invalid"] = true;
            $validations["success"] = false;
            return view("signin", ["validations"=> $validations]);
        };

        if ($password != $confirm_password) {
            $validations["confirm_password"] = "As senhas não podem ser diferentes";
            $validations["invalid"] = true;
            $validations["success"] = false;
            return view("signin", ["validations"=> $validations]);
        };
        
        $validations["invalid"] = false;
        $validations["success"] = true;

        $userData = User::where("email", "=", $email)->first();

        if(isset($userData->email)){
            return redirect()->back()->withErrors(['email' => 'Este email já existe na base de dados']);
        };

        $user = new User();
        $user->firstName = $firstName;
        $user->lastName = $lastName;
        $user->email = $email;
        $user->fone = $fone;
        $user->cpf = $cpf;
        $user->password = Hash::make($password);
        $user->uuid = Uuid::uuid4();

        $user->save();

        auth()->login($user);

        return redirect()->route('login');
    }

    /**
     * retorna uma formulário de LOGIN
     */
    public function loginCreate(){
        return view("login");
    }

    /**
     * Valida o login de uma usuário
     * @params { Array }: [ email, senha ]
     */
    public function loginValid(Request $request) {

        $request->validate(
            [
                'email' => ['required', 'max:50'],
                'password' => ['required'],
            ],
            [
                'email.required' => 'email é obrigatório',
                'email.max' => 'O email tem no máximo 50 caracteres',
                'password.required' => 'A senha é obrigatória',
            ]
        );

        $credentials = $request->only('email', 'password');

        $valid = Auth::validate($credentials);

        if(!Auth::validate($credentials)) {
            return redirect()->back()->withErrors(['email' => 'Verifique se o email foi digitado corretamente.', 'password' => 'Verifique se a senha foi digitada corretamnete.']);
        };

        $user = Auth::getProvider()->retrieveByCredentials($credentials);
        Auth::login($user);

        return redirect()->intended('dashboard');
        
    }

    public function logout() {

        Session::flush();
        Auth::logout();

        return redirect()->intended('home');


    }

    /**
     * retorn uma usuário com base na ID
     * @params { Array }: [ id]
     */
    public function show(Request $request) { }

    /**
     * mostrar aum alista de usuário
     */
    public function list() {

        foreach (User::all() as $user ) {
            var_dump($user);
        }

    }
}