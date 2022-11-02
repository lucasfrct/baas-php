<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Ramsey\Uuid\Uuid;

use App\Models\Transaction;
use App\Types\TransactionStatusType;
use App\Types\TransactionType;

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
        $transaction->tax_package = 'tax_package';
        $transaction->tax_amount = 54;
        $transaction->type = 'type';
        $transaction->status = TransactionStatusType::Processing;

        $transaction->save();

    }

    public function insert($amount, $payer_document, $payer_uuid, $payerBank, $receipient_document, $receipient_uuid, $receipientBank, $tax_package, $tax_amount){
        
        $transaction = new Transaction();
    
        $transaction->uid = Uuid::uuid4();
        $transaction->amount = $amount;
        $transaction->payer_document = $payer_document;
        $transaction->payer_uuid = $payer_uuid;
        $transaction->payer_bank_name = "Jumeci";
        $transaction->payer_bank_code = "001";
        $transaction->payer_bank_ispb = 1;
        $transaction->payer_bank_branch = $payerBank->branch;
        $transaction->payer_bank_number = $payerBank->number;
        $transaction->payer_bank_operator = $payerBank->operator;
        $transaction->receipient_document = $receipient_document;
        $transaction->receipient_uuid = $receipient_uuid;
        $transaction->receipient_bank_name = "Jumeci";
        $transaction->receipient_bank_code = "001";
        $transaction->receipient_bank_ispb = 2;
        $transaction->receipient_bank_branch = $receipientBank->branch;
        $transaction->receipient_bank_number = $receipientBank->number;
        $transaction->receipient_bank_operator = $receipientBank->operator;
        $transaction->tax_package = implode(";", $tax_package);
        $transaction->tax_amount = $tax_amount;
        $transaction->type = TransactionType::CashOut;
        $transaction->status = TransactionStatusType::Processing;
        
        // dd("funfou", $transaction->tax_package);

        $transaction->save();

    }
}
