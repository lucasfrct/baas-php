<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use App\Models\BankAccount;
use App\Models\Transaction;
use App\Types\TransactionType;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\BankNetworkController;
use DateTime;

class BalanceController extends BaseController
{
    public function currentMonth(string $uuid): int
    {
        $bankAccount = BankAccount::where("uuid", "=", $uuid)->first();

        return $bankAccount->balance + $bankAccount->prev_balance;
    }

    public function currentMonthByIspb(string $ispb): int
    {
        $bankNetworkController = new BankNetworkController;

        $bankNetwork = $bankNetworkController->showByIspb($ispb);

        return $bankNetwork->balance + $bankNetwork->prev_balance;
    }

    public function lastMonth(string $uuid){
        $bankAccount = BankAccount::where("uuid", "=", $uuid)->first();

        return $bankAccount->prev_balance;
    }

    public function lastMonthByIspb(string $ispb){
        $bankNetworkController = new BankNetworkController;

        $bankNetwork = $bankNetworkController->showByIspb($ispb);

        return $bankNetwork->prev_balance;
    }

    public function processTransactions(string $uuid): int{
        // ToDo: refatorar a query de transacao
        $transactionsPayer = Transaction::where("payer_uuid", "=", $uuid);
        $transactionsRecipient = Transaction::where("receipient_uuid", "=", $uuid);
        $transactions = array_merge($transactionsPayer, $transactionsRecipient);
        
        $amount = 0;
        foreach ($transactions as $transaction) {
            if ($transaction->type == TransactionType::CashIn) {
                $amount += $transaction->amount;
            }

            if ($transaction->type == TransactionType::CashOut) {
                $amount -= $transaction->amount;
            }
        }

        $bankAccount = new BankAccount();
        $bankAccount->balance = $amount;
        $bankAccount->save();

        return $amount;
    }

    // filtrar por data - do primeiro dia do mes atual ate o dia atual
    public function updateBankAccountByUuid(string $uuid): int{

        $transactionController = new TransactionController();
        $bankAccountController = new BankAccountController();

        $bankAccountData = $bankAccountController->showByUuid($uuid);
        $bankAccountData->balance = $bankAccountData->prev_balance + $transactionController->showMonthBalanceByUuid($uuid, date('m'));
        $bankAccountData->updated_at = new Datetime();
        $bankAccountData->save();

        return $bankAccountData->balance;
    }

    public function updateBankNetworkByUid(string $uid): int{

        $transactionController = new TransactionController();
        $bankNetworkController = new BankNetworkController();

        $bankNetworkData = $bankNetworkController->showByUid($uid);
        $bankNetworkData->balance = $bankNetworkData->prev_balance + $transactionController->showMonthBalanceByUid($uid, date('m'));
        $bankNetworkData->updated_at = new Datetime();
        $bankNetworkData->save();

        return $bankNetworkData->balance;
    }
}