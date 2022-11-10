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
use App\Models\Account;
use App\Models\BankAccount;
use App\Shared\Str;
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
use App\Types\TransactionType;
use App\Types\OperatorType;
use App\Types\GenderType;
use App\Exceptions\Error;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
               
        $parentController = new ParentController();
        $accountController = new AccountController();
        $certificateController = new CertificateController();
        $bankAccountController = new BankAccountController();
        
        $documentIssuer = $parentController->findDocument();
        
        $user = $this->record($form["firstName"], $form["lastName"], $form["email"], $form["fone"], $form["document"], $form["password"]);
        $accountController->record($user->uuid, '', '', GenderType::Empty, [], []);        
        $bankAccount = $bankAccountController->record($user->uuid);        
        $certificate = $certificateController->generate($bankAccount->branch, $bankAccount->number, $documentIssuer, $user->document);
        $accountController->insertCertificateByUuid($user->uuid, $certificate);

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
        
        $bankAccount = new BankAccount();
        $taxController = new TaxController();
        $package = new PackageController();
        $transaction = new TransactionController();
        $balanceController = new BalanceController();
        $accountController = new AccountController();
        $bankAccountController = new BankAccountController();
        $bankNetworkController = new BankNetworkController();
        $integrationController = new IntegrationController();

        
        //dd("funfou");
                
        $payerUuid = '266af967-29f7-47ec-ab82-4b9d0a1b49eb';
        $amount = 5000;
        $transactionType = TransactionType::CashIn->value;
        
        $receipientBankBranch = '001';
        $receipientBankNumber = '000002';
        $receipientBankOperator = OperatorType::Checking->value;
        
        
        // ? ####################################################################################################
        // ? CONSULTA SE O USUARIO PAGADOR EXISTE
        // ? ####################################################################################################
        
        $payerData = $this->showByUuid($payerUuid);
        if (!$payerData) {
            throw new Error('O usuario emitente nao foi encontrado!');
        }
        
        $payerAccountData = $accountController->showByUuid($payerUuid);
        if (!$payerAccountData) {
            throw new Error('A conta emitente nao foi encontrada!');
        }
        
        if (!$payerAccountData->enabled) {
            throw new Error('A conta do usuario esta desativada por tempo indeterminado!');
        }

        // ? ####################################################################################################
        // ? CONSULTA A CONTA BANCARIA DO USUARIO PAGADOR
        // ? ####################################################################################################
        
        $payerBankAccount = $bankAccountController->showByUuid($payerUuid);
        if (!$payerBankAccount) {
            throw new Error('A conta bancaria do usuario emissor nao existe!');
        }
        
        if (!$payerBankAccount->enabled) {
            throw new Error('A conta bancaria do usuario emissor esta desativada por tempo indeterminado!');
        }
        
        // ? ####################################################################################################
        // ? BUSCA OS PACOTES DO USUARIO PAGADOR
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
        // ? CONSULTA AO SALDO DO USUARIO PAGADOR
        // ? ####################################################################################################
        
        $savings = $balanceController->currentMonth($payerUuid);
        if ($savings < $amountCharge) {
            throw new Error('Saldo insuficiente!');
        }
        
        // ? ####################################################################################################
        // ? CONSULTA A CONTA BANCARIA DO USUARIO RECEBEDOR / CARREGA O BANCO DO RECEBEDOR
        // ? ####################################################################################################
        
        $receipientBankAccount = $bankAccountController->showByNumber($receipientBankBranch, $receipientBankNumber, $receipientBankOperator);
        if (!$receipientBankAccount) {
            throw new Error('A conta bancaria do usuario recebedor nao existe!');
        }
        
        if (!$receipientBankAccount->enabled) {
            throw new Error('A conta bancaria do usuario recebedor esta desativada por tempo indeterminado!');
        }
        
        // ? ####################################################################################################
        // ? CONSULTA SE O USUARIO RECEBEDOR EXISTE
        // ? ####################################################################################################
        
        $receipientData = $this->showByUuid($receipientBankAccount->uuid);
        if (!$receipientData) {
            throw new Error('O usuario emitente nao foi encontrado!');
        }
        
        $receipientAccountData = $accountController->showByUuid($receipientData->uuid);
        if (!$receipientAccountData) {
            throw new Error('A conta emitente nao foi encontrada!');
        }
        
        if (!$receipientAccountData->enabled) {
            throw new Error('A conta do usuario esta desativada por tempo indeterminado!');
        }
        
        // ? ####################################################################################################
        // ? CARREGANDO A INTEGRACAO DA REDE BANCARIA DO PAGADOR
        // ? ####################################################################################################
        
        if (count($payerAccountData->integrations) == 0) {
            throw new Error('Usuario nao possui integracao com a rede bancaria!');
        };
        
        $integrations = [];

        foreach ($payerAccountData->integrations as $code) {

            $integrationData = $integrationController->showByCode($code);
            if (!$integrationData || $integrationData->type != $transactionType) {
                continue;
            };
            
            $integrations[] = $integrationData;
        };
        
        if (count($integrations) == 0) {
            throw new Error('Usuario nao possui integracao para essa transacao!');
        };
        
        
        // ? ####################################################################################################
        // ? SELECIONANDO AS CONTAS BOLSAO E DISTRIBUINDO O MARKUP
        // ? ####################################################################################################
        
        // Normalmente havera uma integracao para cada transacao
        // Ainda esta em analise para multiplas integracoes

        $banksReceipients = [];
        foreach ($integrations as $integration) {// 2^2
            $banksReceipients = array_merge($banksReceipients, $bankNetworkController->taxFilter($integration, $packages));            
        };

        dd('funfou');

        
        // ? ####################################################################################################
        // ? REGISTRA A TRANSACAO
        // ? ####################################################################################################
        
        $transaction->insert($amount, $payerData->document, $payerUuid, $payerBankAccount, $receipientData->document, $receipientData->Uuid, $receipientBankAccount, $payerAccountData->packages, $packagesAmount);
                
        // ? ####################################################################################################
        // ? REGISTRA AS TRANSACOES DO EMISSOR PARA AS INTEGRACOES
        // ? ####################################################################################################

        foreach ($banksReceipients as $bank) {

            $transaction->insert($bank->tax_amount, $payerData->document, $payerUuid, $payerBankAccount, $bank->document, $bank->uid, $bank, $bank->packages_codes, 0);
        }

        // ? ####################################################################################################
        // ? ATUALIZA STATUS DA TRANSACAO
        // ? ####################################################################################################
        
        dd("funfou");
        
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

    public function seeder() {

        $parentController = new ParentController();
        $taxController = new TaxController();
        $package = new PackageController();
        $bankNetworkController = new BankNetworkController();
        $integrationController = new IntegrationController();

        $parentController->seed();

        $taxController->seed();
        $package->seed();

        $bankNetworkController->seed();
        $integrationController->seed();
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

