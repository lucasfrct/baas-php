<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ValidatedInput;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\MessageBag;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Parents;
use App\Shared\Str;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\TransactionController;
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
        
        $request->validate(
            [
                'firstName' => ['required', 'max:50'],
                'lastName' => ['required', 'max:50'],
                'email' => ['required', 'max:50'],
                'document' => ['required', 'max:50'],
                'fone' => ['required', 'max:50'],
                'password' => ['required', 'max:50'],
                'confirm_password' => ['required', 'max:50'],
                
            ],
            [
                'firstName.required' => 'O primeiro nome é obrigatório',
                'firstName.max' => 'O primeiro nome tem no máximo 50 caracteres',
                'lastName.required' => 'O ultimo nome é obrigatório',
                'lastName.max' => 'O ultimo nome tem no máximo 50 caracteres',
                'email.required' => 'email é obrigatório',
                'email.max' => 'O email tem no máximo 50 caracteres',
                'document.required' => 'cpf/cnpj é obrigatório',
                'document.max' => 'O cpf/cnpj tem no máximo 50 caracteres',
                'fone.required' => 'O telefone é obrigatório',
                'fone.max' => 'O telefone tem no máximo 50 caracteres',
                'password.required' => 'A senha é obrigatório',
                'password.max' => 'A senha tem no máximo 50 caracteres',
                'confirm_password.required' => 'A confirmação de senha é obrigatório',
                'confirm_password.max' => 'A confirmação de senha tem no máximo 50 caracteres'
            ]
        );

        $form = $request->all();
        
        // $userData = User::where("email", "=", $form["email"])->first();
        
        // if(isset($userData->email)){
        //     return redirect()->back()->withErrors(['email' => 'Este email já existe na base de dados']);
        // };        
        $user = new User();
        $parent = new Parents();
        $parentController = new ParentController();
        $account = new AccountController();
        $certificate = new CertificateController();
        $bankAccount = new BankAccountController();
        
        $user->firstName = $form["firstName"];
        $user->lastName = $form["lastName"];
        $user->email = $form["email"];
        $user->fone = $form["fone"];
        $user->document = Str::padDocument($form["document"]);
        $user->password = Hash::make($form["password"]);
        $user->uuid = Uuid::uuid4();
        $user->save();

        $id = $account->init($user->uuid);        

        $bAccount = $bankAccount->init($user->uuid, $user->document);

        // $parentController->store();
        $documentIssuer = $parentController->findDocument();
        
        $cert = $certificate->generate($bAccount->branch, $bAccount->number, $documentIssuer, $user->document);
        $account->insertCertificate($id, $cert);
        dd($cert);
        // createAccount();
        $this->porcaria();
        
        auth()->login($user);

        return redirect()->route('login');
    }

    public function porcaria() {

        $tax = new TaxController();
        $package = new PackageController();
        $transaction = new TransactionController();
        
        $tax->store();
        $package->store('pkg01', '001');
        $transaction->store();
        
        
        // dd("funfou");


        // iniciando a transacao
        // consulta se o usuario emitente existe
        // puxa os pacotes desse usuario
        // somar a transacao mais o valor do pacote
        // consulta se o usuario tem saldo para pagar a transacao mais a tarifa do pacote
        // carrega o banco do emissor
        // consulta se o usuario receipient existe 
        // carrega o banco do receptor
        // registra a transacao
        // atualizar status da transacao
    }

    // colocar na tabela de account o campo packages_codes

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

