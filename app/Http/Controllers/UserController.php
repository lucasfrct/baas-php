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
use App\Shared\Str;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\BankNetworkController;
use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\BanksListController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\TransactionController;
use App\Types\TransactionType;
use App\Types\OperatorType;
use App\Types\GenderType;
use DateTime;
use App\Exceptions\Error;

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
        
        // $this->seeder();

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
               
        $parentController = new ParentController();
        $accountController = new AccountController();
        $certificateController = new CertificateController();
        $bankAccountController = new BankAccountController();
        $banksListController = new BanksListController;
        $parentController = new ParentController;
        
        $birthday = new DateTime();

        $user = $this->record($form["firstName"], $form["lastName"], $form["email"], $form["fone"], $form["document"], $form["password"]);
        $accountController->record($user->uuid, '', $birthday, GenderType::Empty, ["001"], ["001"]);        

        $bankIspb = $parentController->currentBankIspb();
        $bank = $banksListController->showByIspb($bankIspb);
        if (!$bank) {
            throw new Error('Nao foi encontrado um banco disponivel!');
        }
        
        $bankAccount = $bankAccountController->record($user->uuid, $bank->code);
        if (!$bankAccount) {
            throw new Error('Nao foi possivel criar conta bancaria!');
        }

        $certificate = $certificateController->generate($bankAccount->branch, $bankAccount->number, $bank->document, $user->document);

        $check = $accountController->insertCertificateByUuid($user->uuid, $certificate);
        if (!$check) {
            throw new Error('Nao foi possivel inserir certificado!');
        }

        auth()->login($user);
        
        //return redirect()->route('login');
        return redirect()->intended('dashboard'); // ToDo tirar depois de pronto
    }

    public function record(string $firstName, string $lastName, string $email, string $fone, string $document, string $password): User{

        $user = new User();

        $user->firstName = $firstName;
        $user->lastName = $lastName;
        $user->email = $email;
        $user->fone = $fone;
        $user->document = Str::padDocument($document);
        $user->password = Hash::make($password);
        $user->uuid = Uuid::uuid4();
        $user->save();

        return $user;
    }
    
    public function sumulationTransaction() {

        $payerUuid = '1d89d46c-7630-4c7e-ba3f-5c2ab305d344';
        $amount = 1000;
        $transactionType = TransactionType::CashOut;
        $payerBankIspb = 18236120;
        
        $receipientBankIspb = 18236120;
        $receipientBankBranch = '001';
        $receipientBankNumber = '000002';
        $receipientBankOperator = OperatorType::Checking;        
        
        $transactionController = new TransactionController();
        // $transactionController->operate($payerUuid, $amount, $transactionType, $payerBankIspb, $receipientBankIspb, $receipientBankBranch, $receipientBankNumber, $receipientBankOperator);
        return;

        //# iniciando a transacao: 
        // 
        // consulta se o usuario emitente existe
        // puxa os pacotes desse usuario
        // somar a transacao mais o valor do pacote
        // carrega o banco do emissor
        // consulta se o usuario tem saldo para pagar a transacao mais a tarifa do pacote
        // consulta se o usuario receipient existe 
        // carrega o banco do receptor
        // carregar a integracao do pacote(qual a rede bancaria utilizada)
        // selecionar as contas bolsao e distribuir o markup das taxas
        // registra a transacao do emitente para o recebedor
        // registrar as transacoes do emitente para as integracoes
        // atualizar status da transacao
    }

    public function seeder() 
    {
        $parentController = new ParentController();
        $taxController = new TaxController();
        $packageController = new PackageController();
        $banksList = new BanksListController;
        $bankNetworkController = new BankNetworkController();
        $integrationController = new IntegrationController();

        $parentController->seed();

        $taxController->seed();
        $packageController->seed();

        $bankNetworkController->seed();
        $integrationController->seed();
        $banksList->seed();
    }

    public function showByUuid($uuid): User
    {
       return User::where("uuid", "=", $uuid)->first();
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