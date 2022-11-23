<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use App\Models\BankAccount;
use App\Models\Transaction;
use App\Types\TransactionType;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BankAccountController;

class BalanceController extends BaseController
{
    public function currentMonth(string $uuid) {
        // $this->processTransactions($uuid);
        $bankAccount = BankAccount::where("uuid", "=", $uuid)->first();

        return $bankAccount->balance;
    }

    public function lastMonth(string $uuid){
        $bankAccount = BankAccount::where("uuid", "=", $uuid)->first();

        return $bankAccount->prev_balance;
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
    public function processTransactionsBanksNetwork(string $uid): int{

        $transactionController = new TransactionController();
        $bankAccountController = new BankAccountController();

        dd($transactionController->showMonthBalanceByUid($uid, '11'));

        // ToDo: refatorar a query de transacao
        
        $transactions = $transactionController->showByUid($uid);
        
        $amount = 0;
        foreach ($transactions as $transaction) {
            if ($transaction->type == TransactionType::CashIn) {
                $amount += $transaction->amount;
            }

            if ($transaction->type == TransactionType::CashOut) {
                $amount -= $transaction->amount;
            }
        }

        $bankAccountController->setAmount($amount);// chamar a controller do bankAccount por uid

        return $amount;
    }
}
