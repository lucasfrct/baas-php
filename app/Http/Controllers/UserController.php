<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    /**
     * Get da rota sign in
     * return: retorna a view sign in
     */
    public function index(){
        $validations = [];
        $validations["invalid"] = false;
        $validations["success"] = false;

        return view("signin", ["validations"=> $validations]);
    }

    public function init(){
        $validations = [];
        $validations["invalid"] = false;
        $validations["success"] = false;

        return view("login");
    }

    public function store(Request $request){
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

        $user = new User();
        $user->firstName = $firstName;
        $user->lastName = $lastName;
        $user->email = $email;
        $user->fone = $fone;
        $user->cpf = $cpf;
        $user->password = Hash::make($password);

        $user->save();

        return view("signin", ["validations"=> $validations]);
    }

    public function auth(Request $request) {

        $user = new User();
        $user->name = "Lucas";
        $user->email = "abc@abc.com";
        $user->password = Hash::make("Alterar@123");

        $user->save();

        return view("home");
    }

    public function list() {

        foreach (User::all() as $user ) {
            var_dump($user);
        }

    }

    public function login(Request $request) {

        $form = $request->all();

        $validations = [];
        $validations["invalid"] = false;
        $validations["success"] = false;
        
        $userData = User::where("name", "=", $form["username"])->first();
        dd($userData->name, $userData->password, Hash::make(trim($form["password"])));
        if($userData->password == Hash::make($form["password"])){
            $validations["invalid"] = true;
            return view("home");
        }

        return view("login", ["validations", $validations]);

    }
}