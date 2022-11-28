<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Ramsey\Uuid\Uuid;

use App\Models\User;
use App\Models\Account;
use App\Models\BankAccount;
use App\Models\BanksList;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\BankNetworkController;
use App\Http\Controllers\IntegrationController;
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

    public function cashIn($amount, $payerData, $payerBank, $payerBankAccount, $receipientData, $receipientBank, $receipientBankAccount, $packages, $tax_amount): Transaction
    {
        $type = TransactionType::CashIn->value;
        return $this->insert($amount, $payerData, $payerBank, $payerBankAccount, $receipientData, $receipientBank, $receipientBankAccount, $packages, $tax_amount, $type);
    }

    public function cashOut($amount, $payerData, $payerBank, $payerBankAccount, $receipientData, $receipientBank, $receipientBankAccount, $packages, $tax_amount): Transaction
    {
        $type = TransactionType::CashOut->value;
        return $this->insert($amount, $payerData, $payerBank, $payerBankAccount, $receipientData, $receipientBank, $receipientBankAccount, $packages, $tax_amount, $type);
    }

    private function insert($amount, $payerData, $payerBank, $payerBankAccount, $receipientData, $receipientBank, $receipientBankAccount, $packages, $tax_amount, $type): Transaction
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
        $transaction->packages = $packages;
        $transaction->tax_amount = $tax_amount;
        $transaction->type = $type;
        $transaction->status = TransactionStatusType::Transient->value;
        
        $transaction->save();
        //dd("funfou transaction", $transaction);

        return $transaction;
    }

    public function updateStatus(string $uid, string $status): Transaction
    {
        $transaction = Transaction::where("uid", "=", $uid)->first();

        $transaction->status = $status;

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

    public function operate(
        string $payerUuid, 
        int $amount, 
        TransactionType $type, 
        int $payerBankIspb, 
        int $receipientBankIspb, 
        string $receipientBankBranch, 
        string $receipientBankNumber, 
        OperatorType $receipientBankOperator
    ) {
        
        $packageController = new PackageController();
        $balanceController = new BalanceController();
        $accountController = new AccountController();
        $bankAccountController = new BankAccountController();
        $bankNetworkController = new BankNetworkController();
        $banksListController = new BanksListController();
        $integrationController = new IntegrationController();

    }

    public function prepare(
        string $payerUuid, 
        int $amount, 
        TransactionType $type, 
        int $payerBankIspb, 
        int $receipientBankIspb, 
        string $receipientBankBranch, 
        string $receipientBankNumber, 
        OperatorType $receipientBankOperator
    ) {

        $packageController = new PackageController();
        $balanceController = new BalanceController();
        $accountController = new AccountController();
        $bankAccountController = new BankAccountController();
        $bankNetworkController = new BankNetworkController();
        $banksListController = new BanksListController();
        $integrationController = new IntegrationController();

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
        
        // Banco emissor da conta do usuario pagador
        $payerBank = $banksListController->showByIspb($payerBankIspb);
        
        $payerBankAccount = $bankAccountController->showByUuid($payerAccountData->uuid);
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
            
            foreach ($payerAccountData->packages as $code) {
                $payerPackageData = $packageController->showByCode($code);
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
        if ($savings <= $amountCharge) {
            throw new Error('Saldo insuficiente!');
        }
        
        // ? ####################################################################################################
        // ? CONSULTA A CONTA BANCARIA DO USUARIO RECEBEDOR / CARREGA O BANCO DO RECEBEDOR
        // ? ####################################################################################################
        
        $receipientBank = $banksListController->showByIspb($receipientBankIspb);
        if (!$receipientBank) {
            throw new Error('Banco recebedor nao encontrado!');
        }
        
        $receipientBankAccount = $bankAccountController->showByNumber($receipientBank->code, $receipientBankBranch, $receipientBankNumber, $receipientBankOperator);
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
            if (!$integrationData || $integrationData->type != $type) {
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

    }

    public function apply(
        int $amount, 
        User $payerData, 
        BanksList $payerBank , 
        BankAccount $payerBankAccount, 
        array $receipientData, 
        array $receipientBank, 
        array $receipientBankAccount, $packages, $packagesAmount, $banksReceipients) {

        $banksListController = new BanksListController();

        // ? ####################################################################################################
        // ? REGISTRA A TRANSACAO
        // ? ####################################################################################################
        
        $transactionsPool = [];
        
        $transactionData = $this->cashOut($amount, $payerData, $payerBank , $payerBankAccount, $receipientData, $receipientBank, $receipientBankAccount, $packages, $packagesAmount);
        $transactionsPool[] = $transactionData;
        
        // ? ####################################################################################################
        // ? REGISTRA AS TRANSACOES DA REDE BANCARIA (INTEGRACOES)
        // ? ####################################################################################################
        
        foreach ($banksReceipients as $bank) {
            
            $bankData = $banksListController->showByIspb($bank->ispb);
            if (!$bankData) {
                continue;
            }
            $transactionsPool[] = $this->cashOut($bank->tax_amount, $payerData, $payerBank, $payerBankAccount, $bank, $bankData, $bank, $packages, 0);
            sleep(1);
        }

    }
}

// $this->execute();
// $this->register();
// $this->send();
// $this->do();
// $this->make();
// $this->apply();
