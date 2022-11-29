<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use Ramsey\Uuid\Uuid;
use App\Models\BanksNetwork;
use App\Types\OperatorType;

class BankNetworkController extends BaseController
{
    public function seed(){

        $this->record("XXX000", '010', OperatorType::Checking, 18236120, "001", ["001"], '24410913000144', 10000, 10000);
    }

    public function record(string $number, string $branch, OperatorType $operator, int $ispb, string $code, array $tax_codes, string $document, int $prev_balance, int $balance){

        $bankNetwork = new BanksNetwork();
        $bankNetwork->uid = Uuid::uuid4();
        $bankNetwork->number = $number;
        $bankNetwork->branch = $branch;
        $bankNetwork->operator = $operator;
        $bankNetwork->ispb = $ispb;
        $bankNetwork->code = $code;
        $bankNetwork->tax_codes = $tax_codes;
        $bankNetwork->document = $document;
        $bankNetwork->enabled = TRUE;
        $bankNetwork->prev_balance = $prev_balance;
        $bankNetwork->balance = $balance;

        $bankNetwork->save();
    }

    public function taxBillings(string $uid, int $amount){

        $bankNetworkData = BanksNetwork::where("uid", "=", $uid)->first();

        if (!$bankNetworkData->balance) {
            $bankNetworkData->balance = 0;
        };

        $bankNetworkData->balance += $amount;

        $bankNetworkData->save();
    }

    public function taxFilter($integration, array $packages): array {
        $banksReceipients = [];
        foreach ($integration->bankNetwork as $bank) {// 2^2*2^3
            foreach ($bank->tax_codes as $code) {// 2^3*2^4
                foreach ($packages as $package) {// 2^4*2^5
                    foreach ($package->taxes as $tax) {// 2^5*2^6
                        if ($code == $tax->code) {
                            $bank->tax_amount = $tax->amount;
                            $bank->packages_codes[] = $package->code;
                            $banksReceipients[] = clone $bank;
                        };
                    }
                }                
            }
        }

        return $banksReceipients;        
    }

    public function showByCode($code): BanksNetwork
    {
        return BanksNetwork::where("code", "=", $code)->first();
    }
    
    public function showByUid($uid): BanksNetwork
    {
        return BanksNetwork::where("uid", "=", $uid)->first();
    }
}