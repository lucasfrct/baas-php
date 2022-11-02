<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use App\Models\BankAccount;
use App\Models\Transaction;
use App\Types\TransactionType;
use App\Http\Controllers\TransactionController;

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
}
