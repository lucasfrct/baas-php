<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

use Ramsey\Uuid\Uuid;

use App\Models\User;
use App\Models\Account;
use App\Models\BankAccount;
use App\Models\BanksList;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\BankNetworkController;
use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BanksListController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\PackageController;
use App\Exceptions\Error;
use App\Models\Transaction;
use App\Types\TransactionStatusType;
use App\Types\TransactionType;
use App\Types\OperatorType;

class TransactionController extends BaseController
{
    private PackageController $packageController;
    private BalanceController $balanceController;
    private AccountController $accountController;
    private UserController $userController;
    private BankAccountController $bankAccountController;
    private BankNetworkController $bankNetworkController;
    private BanksListController $banksListController;
    private IntegrationController $integrationController;

    private array $packages = [];
    private array $integrations = [];
    private array $banksReceipients = [];
    private array $transactionsPool = [];
    private User $payerData;
    private User $receipientData;
    private Account $payerAccountData;
    private Account $receipientAccountData;
    private BanksList $payerBank;
    private BanksList $receipientBank;
    private BankAccount $payerBankAccount;
    private BankAccount $receipientBankAccount;
    private int $packagesAmount;
    private int $amountCharge;

    public User $payerUser;
    public User $receipientUser;


    public function __construct() {
        $this->packageController = new PackageController();
        $this->balanceController = new BalanceController();
        $this->accountController = new AccountController();
        $this->userController = new UserController();
        $this->bankAccountController = new BankAccountController();
        $this->bankNetworkController = new BankNetworkController();
        $this->banksListController = new BanksListController();
        $this->integrationController = new IntegrationController();

    }

    public function store(){
        
        $transaction = new Transaction();
    
        $transaction->uid = Uuid::uuid4();
        $transaction->amount = 12345678;
        $transaction->payer_document = '1234567890';
        $transaction->payer_uuid = 'payer';
        $transaction->payer_bank_name = 'payer';
        $transaction->payer_bank_code = 'pay';
        $transaction->payer_bank_ispb = 1;
        $transaction->payer_bank_branch = 'paye';
        $transaction->payer_bank_number = 'payer';
        $transaction->payer_bank_operator = 'pa';
        $transaction->receipient_document = 'receipient';
        $transaction->receipient_uuid = 'receipient';
        $transaction->receipient_bank_name = 'receipient';
        $transaction->receipient_bank_code = 'rec';
        $transaction->receipient_bank_ispb = 2;
        $transaction->receipient_bank_branch = 'rece';
        $transaction->receipient_bank_number = 'recei';
        $transaction->receipient_bank_operator = 're';
        $transaction->tax_package = [];
        $transaction->tax_amount = 54;
        $transaction->type = 'type';
        $transaction->status = TransactionStatusType::Processing;

        $transaction->save();

    }

    public function cashIn($amount, $payerData, $payerBank, $payerBankAccount, $receipientData, $receipientBank, $receipientBankAccount, $tax_amount): Transaction
    {
        $type = TransactionType::CashIn->value;
        return $this->insert($amount, $payerData, $payerBank, $payerBankAccount, $receipientData, $receipientBank, $receipientBankAccount, $tax_amount, $type);
    }

    public function cashOut($amount, $payerData, $payerBank, $payerBankAccount, $receipientData, $receipientBank, $receipientBankAccount, $tax_amount): Transaction
    {
        $type = TransactionType::CashOut->value;
        return $this->insert($amount, $payerData, $payerBank, $payerBankAccount, $receipientData, $receipientBank, $receipientBankAccount, $tax_amount, $type);
    }

    private function insert($amount, $payerData, $payerBank, $payerBankAccount, $receipientData, $receipientBank, $receipientBankAccount, $tax_amount, $type): Transaction
    {
        
        $transaction = new Transaction();
    
        $transaction->uid = Uuid::uuid4();
        $transaction->amount = $amount;
        $transaction->payer_document = $payerData->document;
        $transaction->payer_uid = $payerData->uid ?? '';
        $transaction->payer_uuid = $payerData->uuid ?? '';
        $transaction->payer_bank_company = $payerBank->company;
        $transaction->payer_bank_code = $payerBankAccount->parent_code ?? $payerBankAccount->code;
        $transaction->payer_bank_ispb = $payerBank->ispb;
        $transaction->payer_bank_branch = $payerBankAccount->branch;
        $transaction->payer_bank_number = $payerBankAccount->number;
        $transaction->payer_bank_operator = $payerBankAccount->operator;
        $transaction->receipient_document = $receipientData->document;
        $transaction->receipient_uid = $receipientData->uid ?? '';
        $transaction->receipient_uuid = $receipientData->uuid ?? '';
        $transaction->receipient_bank_company = $receipientBank->company;
        $transaction->receipient_bank_code = $receipientBankAccount->parent_code ?? $receipientBankAccount->code;
        $transaction->receipient_bank_ispb = $receipientBank->ispb;
        $transaction->receipient_bank_branch = $receipientBankAccount->branch;
        $transaction->receipient_bank_number = $receipientBankAccount->number;
        $transaction->receipient_bank_operator = $receipientBankAccount->operator;
        $transaction->packages = $this->packages;
        $transaction->tax_amount = $tax_amount;
        $transaction->type = $type;
        $transaction->status = TransactionStatusType::Transient->value;
        
        $transaction->save();

        return $transaction;
    }

    public function updateStatus(string $uid, TransactionStatusType $status): Transaction
    {
        $transaction = Transaction::where("uid", "=", $uid)->first();

        $transaction->status = $status->value;

        $transaction->save();

        return $transaction;
    }

    public function showByUid($uid): array
    {
        return array_merge($this->showByPayerUid($uid), $this->showByReceipientUid($uid));
    }

    public function showByPayerUid($uid)
    {
       return Transaction::where("payer_uid", "=", $uid)->first();
    }

    public function showByReceipientUid($uid)
    {
       return Transaction::where("receipient_uid", "=", $uid)->first();
    }

    public function showMonthBalanceByUid($uid, $month)
    {
        return $this->showMonthBalanceByKey("uid", $uid, $month);
    }

    public function showMonthBalanceByUuid($uuid, $month)
    {
        return $this->showMonthBalanceByKey("uuid", $uuid, $month);
    }

    private function showMonthBalanceByKey($key, $value, $month)
    {
        $year = strval(date('Y'));
        
        $monthLenthInDays = cal_days_in_month(CAL_GREGORIAN, $month, strval(date('Y')));
        $from = date("{$year}-{$month}-01");
        $until = date("{$year}-{$month}-{$monthLenthInDays}");
        $untilNormalized = date('Y-m-d', strtotime($until. ' + 1 days'));

        $transactions = Transaction::where(function($query) use($key, $value){
        return $query->orWhere("payer_{$key}", "=", $value)->orWhere("receipient_{$key}", "=", $value);
        })->whereBetween('created_at', [$from, $untilNormalized])->get();

        $payeds = 0;
        $receiveds = 0;
        foreach ($transactions as $transaction) {
            if ($key == 'uid' && !empty($transaction->payer_uid) && $transaction->payer_uid == $value) {
                $payeds -= $transaction->amount;
                continue;
            }

            if ($key == 'uid' && !empty($transaction->receipient_uid) && $transaction->receipient_uid == $value) {
                $receiveds += $transaction->amount;
                continue;
            }

            if ($key == 'uuid' && !empty($transaction->payer_uuid) && $transaction->payer_uuid == $value) {
                $payeds -= $transaction->amount;
                continue;
            }
            
            if ($key == 'uuid' && !empty($transaction->receipient_uuid) && $transaction->receipient_uuid == $value) {
                $receiveds += $transaction->amount;
            }
        }
        return $payeds + $receiveds;
    }

    /**
     * * Retorna todas as transacoes dentro de um dado periodo de tempo
     *
     * @param [string] $key: assume dois valores - "uid" e "uuid" - uid para empresas e uuid para o usuario
     * @param [string] $id: uuid ou uid identificador de um usuario ou uma empresa
     * @param [string] $from: data de inicio da pesquisa
     * @param [string] $until: data de fim da pesquisa
     * @return [array] Transaction: Lista de transacoes entre datas
     */
    public function showBetweenDates($key, $id, $from, $until)
    {
        try {
            $userController = new UserController();
            $banksNetworkController = new BankNetworkController();
            $bankListController = new BanksListController();

            $untilNormalized = date('Y-m-d', strtotime($until. ' + 1 days'));
    
            $transactions = Transaction::where(function($query) use($key, $id){
            return $query->orWhere("payer_{$key}", "=", $id)->orWhere("receipient_{$key}", "=", $id);
            })->whereBetween('created_at', [$from, $untilNormalized])->get();
    
            $transactionsData = [];
            foreach ($transactions as $transaction) {

                if (!empty($transaction->payer_uuid)) {
                    $transaction->payerUser = $userController->showByUuid($transaction->payer_uuid);
                }

                if (!empty($transaction->payer_uid)) {
                    $bankNetwork = $banksNetworkController->showByUid($transaction->payer_uid);
                    $transaction->payerUser = $bankListController->showByIspb($bankNetwork->ispb);
                }

                if (!empty($transaction->receipient_uuid)) {
                    $transaction->receipientUser = $userController->showByUuid($transaction->receipient_uuid);
                }

                if (!empty($transaction->receipient_uid)) {
                    $bankNetwork = $banksNetworkController->showByUid($transaction->receipient_uid);
                    $transaction->receipientUser = $bankListController->showByIspb($bankNetwork->ispb);
                }
                
                $transactionsData[] = $transaction;
            }

            return $transactionsData;

        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['errorDefault' => $th->getMessage()]);
        }
    }

    public function operate(
        string $payerUuid, 
        int $amount, 
        TransactionType $type, 
        int $payerBankIspb,
        OperatorType $payerBankOperator,
        int $receipientBankIspb, 
        string $receipientBankBranch, 
        string $receipientBankNumber, 
        OperatorType $receipientBankOperator
    ) {        
        $this->prepare($payerUuid, $amount, $type, $payerBankIspb, $payerBankOperator, $receipientBankIspb, $receipientBankBranch, $receipientBankNumber, $receipientBankOperator);

        $this->apply($amount);

        $this->liquidate();
    }

    public function prepare(
        string $payerUuid, 
        int $amount, 
        TransactionType $type, 
        int $payerBankIspb, 
        OperatorType $payerBankOperator,
        int $receipientBankIspb, 
        string $receipientBankBranch, 
        string $receipientBankNumber, 
        OperatorType $receipientBankOperator
    ): array {
        // ? ####################################################################################################
        // ? CONSULTA SE O USUARIO PAGADOR EXISTE
        // ? ####################################################################################################
        
        $this->payerData = $this->userController->showByUuid($payerUuid);
        if (!$this->payerData) {
            throw new Error('O usuario emitente nao foi encontrado!');
        }
        
        $this->payerAccountData = $this->accountController->showByUuid($payerUuid);
        if (!$this->payerAccountData) {
            throw new Error('A conta emitente nao foi encontrada!');
        }
        
        if (!$this->payerAccountData->enabled) {
            throw new Error('A conta do usuario esta desativada por tempo indeterminado!');
        }
        
        // ? ####################################################################################################
        // ? CONSULTA A CONTA BANCARIA DO USUARIO PAGADOR
        // ? ####################################################################################################
        
        // Banco emissor da conta do usuario pagador
        $this->payerBank = $this->banksListController->showByIspb($payerBankIspb);
        
        $this->payerBankAccount = $this->bankAccountController->showByUuid($this->payerAccountData->uuid);
        if (!$this->payerBankAccount) {
            throw new Error('A conta bancaria do usuario emissor nao existe!');
        }
        
        if (!$this->payerBankAccount->enabled) {
            throw new Error('A conta bancaria do usuario emissor esta desativada por tempo indeterminado!');
        }
        
        // ? ####################################################################################################
        // ? BUSCA OS PACOTES DO USUARIO PAGADOR
        // ? ####################################################################################################
        
        $this->packages = [];
        $this->packagesAmount = 0;
        
        if (count($this->payerAccountData->packages) > 0) {
            
            foreach ($this->payerAccountData->packages as $code) {
                $payerPackageData = $this->packageController->showByCode($code);
                if (!$payerPackageData) {
                    continue;
                }
                $this->packages[] = $payerPackageData;
                $this->packagesAmount += $payerPackageData->amount;
            }
        }
        
        $this->amountCharge = $amount + $this->packagesAmount;
        
        // ? ####################################################################################################
        // ? CONSULTA AO SALDO DO USUARIO PAGADOR
        // ? ####################################################################################################
        
        $savings = $this->balanceController->currentMonth($payerUuid);
        if ($savings <= $this->amountCharge) {
            throw new Error('Saldo insuficiente!');
        }
        
        // ? ####################################################################################################
        // ? CONSULTA A CONTA BANCARIA DO USUARIO RECEBEDOR / CARREGA O BANCO DO RECEBEDOR
        // ? ####################################################################################################
        
        $this->receipientBank = $this->banksListController->showByIspb($receipientBankIspb);
        if (!$this->receipientBank) {
            throw new Error('Banco recebedor nao encontrado!');
        }
        
        $this->receipientBankAccount = $this->bankAccountController->showByNumber($this->receipientBank->code, $receipientBankBranch, $receipientBankNumber, $receipientBankOperator);
        if (!$this->receipientBankAccount) {
            throw new Error('A conta bancaria do usuario recebedor nao existe!');
        }
        
        if (!$this->receipientBankAccount->enabled) {
            throw new Error('A conta bancaria do usuario recebedor esta desativada por tempo indeterminado!');
        }
        
        // ? ####################################################################################################
        // ? CONSULTA SE O USUARIO RECEBEDOR EXISTE
        // ? ####################################################################################################
        
        $this->receipientData = $this->userController->showByUuid($this->receipientBankAccount->uuid);
        if (!$this->receipientData) {
            throw new Error('O usuario emitente nao foi encontrado!');
        }
        
        $this->receipientAccountData = $this->accountController->showByUuid($this->receipientData->uuid);
        if (!$this->receipientAccountData) {
            throw new Error('A conta emitente nao foi encontrada!');
        }
        
        if (!$this->receipientAccountData->enabled) {
            throw new Error('A conta do usuario esta desativada por tempo indeterminado!');
        }
        
        // ? ####################################################################################################
        // ? CARREGANDO A INTEGRACAO DA REDE BANCARIA DO PAGADOR
        // ? ####################################################################################################
        
        if (count($this->payerAccountData->integrations) == 0) {
            throw new Error('Usuario nao possui integracao com a rede bancaria!');
        };
        
        $this->integrations = [];
        
        foreach ($this->payerAccountData->integrations as $code) {
            
            $integrationData = $this->integrationController->showByCode($code);
            if (!$integrationData || $integrationData->type != $type->value) {
                continue;
            };
            
            $this->integrations[] = $integrationData;
        };
        
        if (count($this->integrations) == 0) {
            throw new Error('Usuario nao possui integracao para essa transacao!');
        };
        
        // ? ####################################################################################################
        // ? SELECIONANDO AS CONTAS BOLSAO E DISTRIBUINDO O MARKUP
        // ? ####################################################################################################
        
        // Normalmente havera uma integracao para cada transacao
        // Ainda esta em analise para multiplas integracoes
        
        $this->banksReceipients = [];
        foreach ($this->integrations as $integration) {// 2^2
            $this->banksReceipients = array_merge($this->banksReceipients, $this->bankNetworkController->taxFilter($integration, $this->packages));
        };

        return [$this->receipientData, $this->receipientBankAccount, $this->receipientBank, $this->packagesAmount, $this->amountCharge];
    }
    
    public function apply(int $amount) 
    {            
        // ? ####################################################################################################
        // ? REGISTRA A TRANSACAO
        // ? ####################################################################################################
        
        $this->transactionsPool = [];
        
        $this->transactionsPool[] = $this->cashOut(
            $amount, 
            $this->payerData, 
            $this->payerBank , 
            $this->payerBankAccount, 
            $this->receipientData, 
            $this->receipientBank, 
            $this->receipientBankAccount,
            $this->packagesAmount
        );
        
        // ? ####################################################################################################
        // ? REGISTRA AS TRANSACOES DA REDE BANCARIA (INTEGRACOES)
        // ? ####################################################################################################
        
        foreach ($this->banksReceipients as $bank) {
            
            $bankData = $this->banksListController->showByIspb($bank->ispb);
            if (!$bankData) {
                continue;
            }
            $this->transactionsPool[] = $this->cashOut($bank->tax_amount, $this->payerData, $this->payerBank, $this->payerBankAccount, $bank, $bankData, $bank, 0);
            sleep(1);
        }
    }

    public function liquidate() 
    {        
        // ? ####################################################################################################
        // ? ATUALIZA STATUS DA TRANSACAO
        // ? ####################################################################################################
        
        foreach ($this->transactionsPool as $transaction) {
            
            $this->updateStatus($transaction->uid, TransactionStatusType::Processing);
            sleep(1);
        }
        
        // ? ####################################################################################################
        // ? ATUALIZA O BALANCE DO PAGADOR
        // ? ####################################################################################################
        
        $this->balanceController->updateBankAccountByUuid($this->payerData->uuid);
        
        // ? ####################################################################################################
        // ? ATUALIZA O BALANCE DO RECEBEDOR
        // ? ####################################################################################################
        
        $this->balanceController->updateBankAccountByUuid($this->receipientData->uuid);
        
        // ? ####################################################################################################
        // ? ATUALIZA O BALANCE DOS RECEBEDORES DAS TAXAS
        // ? ####################################################################################################
        
        foreach ($this->banksReceipients as $bank) 
        {            
            $this->balanceController->updateBankNetworkByUid($bank->uid);
            sleep(1);
        }
        
        // ? ####################################################################################################
        // ? ATUALIZA STATUS DA TRANSACAO
        // ? ####################################################################################################
        
        foreach ($this->transactionsPool as $transaction) {
            
            $this->updateStatus($transaction->uid, TransactionStatusType::Paided);
            sleep(1);
        }
    }

    public function showPaginationByIspb($ispb, $page, $perpage) 
    {
        $userController = new UserController();
        $banksNetworkController = new BankNetworkController();
        $bankListController = new BanksListController();

        $bankNetwork = $banksNetworkController->showByIspb($ispb);
        $transactions = Transaction::where(function($query) use($bankNetwork){
            return $query->orWhere("payer_uid", "=", $bankNetwork->uid)->orWhere("receipient_uid", "=", $bankNetwork->uid);
        })->paginate($perpage, ["*"], "transactions", $page);

        $transactionsData = [];
            foreach ($transactions as $transaction) {

                if (!empty($transaction->payer_uuid)) {
                    $transaction->payerUser = $userController->showByUuid($transaction->payer_uuid);
                }

                if (!empty($transaction->payer_uid)) {
                    $bankNetwork = $banksNetworkController->showByUid($transaction->payer_uid);
                    $transaction->payerUser = $bankListController->showByIspb($bankNetwork->ispb);
                }

                if (!empty($transaction->receipient_uuid)) {
                    $transaction->receipientUser = $userController->showByUuid($transaction->receipient_uuid);
                }

                if (!empty($transaction->receipient_uid)) {
                    $bankNetwork = $banksNetworkController->showByUid($transaction->receipient_uid);
                    $transaction->receipientUser = $bankListController->showByIspb($bankNetwork->ispb);
                }
                
                $transactionsData[] = $transaction;
            }


        return [$transactionsData, $transactions->total(), $transactions->currentPage(), $transactions->perPage(), $transactions->lastPage()];
    }

    public function typeLabel() 
    {
        $transactionTypeLabel = [];
        foreach (TransactionStatusType::cases() as $item) {
            $name = "";
            switch ($item->value) {
                case 'transient':
                    $name = "Em Transicao";
                    break;
                
                case 'error':
                    $name = "Erro";
                    break;
                
                case 'denied':
                    $name = "Negado";
                    break;

                case 'incomplete':
                    $name = "Incompleto";
                    break;

                case 'processing':
                    $name = "Processando";
                    break;

                case 'paided':
                    $name = "Pago";
                    break;

                case 'canceled':
                    $name = "Cancelado";
                    break;
                                    
                default:
                    $name = $item->value;
                    break;
            }
            $transactionTypeLabel[$item->value] = $name;
        };

        return $transactionTypeLabel;
    }
}