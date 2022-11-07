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
use App\Models\Account;
use App\Models\BankAccount;
use App\Models\Parents;
use App\Models\Package;
use App\Shared\Str;
use App\Types\TransactionType;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\BankNetworkController;
use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\BalanceController;
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
        
        $this->porcaria();
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
        
               
        $user = new User();
        $account = new Account();
        $parent = new Parents();
        $parentController = new ParentController();
        $accountController = new AccountController();
        $certificate = new CertificateController();
        $bankAccountController = new BankAccountController();
        $balance = new BalanceController();
        
        //$parentController->store();

        $user->firstName = $form["firstName"];
        $user->lastName = $form["lastName"];
        $user->email = $form["email"];
        $user->fone = $form["fone"];
        $user->document = Str::padDocument($form["document"]);
        $user->password = Hash::make($form["password"]);
        $user->uuid = Uuid::uuid4();
        $user->save();
        
        $id = $accountController->seed($user->uuid);
        
        $bAccount = $bankAccountController->seed($user->uuid);
        
        $documentIssuer = $parentController->findDocument();
        
        $cert = $certificate->generate($bAccount->branch, $bAccount->number, $documentIssuer, $user->document);
        $accountController->insertCertificate($id, $cert);
        // createAccount();
        dd($cert);
        
        auth()->login($user);
        
        return redirect()->route('login');
    }
    
    public function porcaria() {
        
        $user = new User();
        $bankAccount = new BankAccount();
        $tax = new TaxController();
        $package = new PackageController();
        $transaction = new TransactionController();
        $balanceController = new BalanceController();
        $bankAccountController = new BankAccountController();
        $bankNetworkController = new BankNetworkController();
        $integrationController = new IntegrationController();

        //$bankNetworkController->seed();
        //$integrationController->seed();
        //dd("funfou");
                
        $payerUuid = '266af967-29f7-47ec-ab82-4b9d0a1b49eb';
        $amount = 5000;
        $payerBankBranch = '001';
        $payerBankNumber = '000001';
        $payerBankOperator = '1';
        $transactionType = TransactionType::CashIn;    
        
        $receipientBankBranch = '001';
        $receipientBankNumber = '000002';
        $receipientBankOperator = '1';
        
        
        // ? ####################################################################################################
        // ? CONSULTA SE O USUARIO EMITENTE EXISTE
        // ? ####################################################################################################
        
        $payerData = User::where("uuid", "=", $payerUuid)->first();
        if (!$payerData) {
            throw new Exeption('O usuario emitente nao foi encontrado!');
        }
        
        $payerAccountData = Account::where("uuid", "=", $payerUuid)->first();
        if (!$payerAccountData) {
            throw new Exeption('A conta emitente nao foi encontrada!');
        }
        
        if (!$payerAccountData->enabled) {
            throw new Exeption('A conta do usuario esta desativada por tempo indeterminado!');
        }
        
        // ? ####################################################################################################
        // ? BUSCA OS PACOTES DO USUARIO EMITENTE
        // ? ####################################################################################################
        
        $packages = [];
        $packagesAmount = 0;
        
        if (count($payerAccountData->packages) > 0) {
            
            foreach ($payerAccountData->packages as $value) {
                $payerPackageData = $package->showByCode($value);
                if (!$payerPackageData) {
                    continue;
                }
                $packages[] = $payerPackageData;
                $packagesAmount += $payerPackageData->amount;
            }
        }
        
        $amountCharge = $amount + $packagesAmount;
        
        // ? ####################################################################################################
        // ? CONSULTA A CONTA BANCARIA DO USUARIO EMITENTE
        // ? ####################################################################################################
        
        $payerBankAccount = $bankAccountController->showByUuid($payerUuid);
        if (!$payerBankAccount) {
            throw new Exeption('A conta bancaria do usuario emissor nao existe!');
        }
        
        if (!$payerBankAccount->enabled) {
            throw new Exeption('A conta bancaria do usuario emissor esta desativada por tempo indeterminado!');
        }
        
        // ? ####################################################################################################
        // ? CONSULTA AO SALDO DO USUARIO EMISSOR
        // ? ####################################################################################################
        
        $savings = $balanceController->currentMonth($payerUuid);
        if ($savings < $amountCharge) {
            throw new Exeption('Saldo insuficiente!');
        }
        
        // ? ####################################################################################################
        // ? CONSULTA A CONTA BANCARIA DO USUARIO RECEBEDOR / CARREGA O BANCO DO RECEBEDOR
        // ? ####################################################################################################
        
        $receipientBankAccount = $bankAccountController->showByNumber($receipientBankBranch, $receipientBankNumber);
        if (!$receipientBankAccount) {
            throw new Exeption('A conta bancaria do usuario recebedor nao existe!');
        }
        
        if (!$receipientBankAccount->enabled) {
            throw new Exeption('A conta bancaria do usuario recebedor esta desativada por tempo indeterminado!');
        }
        
        $receipientUuid = $receipientBankAccount->uuid;
        
        
        // ? ####################################################################################################
        // ? CARREGANDO A INTEGRACAO DO PACOTE
        // ? ####################################################################################################
        
        if (count($payerAccountData->integrations) == 0) {
            throw new Exeption('Usuario nao possui integracao com a rede bancaria!');
        }
        
        $integration = [];

        foreach ($payerAccountData->integrations as $code) {
            $integrationData = $integrationController->showByCode($code);
            if (!$integrationData) {
                continue;
            }

            if ($integrationData->type != $transactionType) {
                continue;
            }

            $integration[] = $integrationData;
        }

        if (count($integration) == 0) {
            throw new Exeption('Usuario nao possui integracao para essa transacao!');
        }
        
        // ? ####################################################################################################
        // ? SELECIONANDO AS CONTAS BOLSAO E DISTRIBUINDO O MARKUP
        // ? ####################################################################################################
        
        
        
        // ? ####################################################################################################
        // ? REGISTRA A TRANSACAO
        // ? ####################################################################################################
        
        $payer = $this->showByUuid($payerUuid);
        $receipient = $this->showByUuid($receipientUuid);
        
        $transaction->insert($amountCharge, $payer->document, $payerUuid, $payerBankAccount, $receipient->document, $receipientUuid, $receipientBankAccount, $payerAccountData->packages, $packagesAmount);
        
        
        $payerPackages = [];
        
        // ? ####################################################################################################
        // ? REGISTRA AS TRANSACOES DO EMISSOR PARA AS INTEGRACOES
        // ? ####################################################################################################
        
        dd("funfou");
        $tax->seed();
        $package->seed('pkg01', '001');
        $transaction->store();
        
        
        
        
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

    public function showByUuid($uuid)
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

