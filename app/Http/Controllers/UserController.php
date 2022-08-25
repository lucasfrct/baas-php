<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function index(){
        // return view("user");
        $validations = [];
        $validations["invalid"] = false;
        $validations["success"] = false;

        return view("user", ["validations"=> $validations]);
    }

    public function store(Request $request){
        $form = $request->all();
        
        $validations = [];
        $validations["invalid"] = false;
        $validations["success"] = false;

        $name = $form["name"] ?? "";
        if (empty($name)) {
            $validations["name"] = "campo nome não pode ser vazio";
            $validations["invalid"] = true;
            $validations["success"] = false;
            return view("user", ["validations"=> $validations]);
        };

        $email = $form["email"] ?? "";
        if (empty($email)) {
            $validations["email"] = "campo email não pode ser vazio";
            $validations["invalid"] = true;
            $validations["success"] = false;
            return view("user", ["validations"=> $validations]);
        };

        $cpf = $form["cpf"] ?? "";
        if (empty($cpf)) {
            $validations["cpf"] = "campo cpf não pode ser vazio";
            $validations["invalid"] = true;
            $validations["success"] = false;
            return view("user", ["validations"=> $validations]);
        };

        $address = $form["address"] ?? "";
        if (empty($address)) {
            $validations["address"] = "campo endereço não pode ser vazio";
            $validations["invalid"] = true;
            $validations["success"] = false;
            return view("user", ["validations"=> $validations]);
        };

        $fone = $form["fone"] ?? "";
        if (empty($fone)) {
            $validations["fone"] = "campo telefone não pode ser vazio";
            $validations["invalid"] = true;
            $validations["success"] = false;
            return view("user", ["validations"=> $validations]);
        };

        $message = $form["message"] ?? "";
        if (empty($message)) {
            $validations["message"] = "campo menssagem não pode ser vazio";
            $validations["invalid"] = true;
            $validations["success"] = false;
            return view("user", ["validations"=> $validations]);
        };
        
        $validations["invalid"] = false;
        $validations["success"] = true;
        // return view("user");
        return view("user", ["validations"=> $validations]);
    }
}