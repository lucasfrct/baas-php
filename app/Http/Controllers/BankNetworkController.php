<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use Ramsey\Uuid\Uuid;
use App\Models\BanksNetwork;
use App\Types\OperatorType;

class BankNetworkController extends BaseController
{
    public function seed(){

        $branch = new BranchController();

        $bankNetwork = new BanksNetwork();
        $bankNetwork->uid = Uuid::uuid4();
        $bankNetwork->number = "XXX000";
        $bankNetwork->branch = $branch->getCurrent();
        $bankNetwork->operator = OperatorType::Checking;
        $bankNetwork->bank_ispb = 1;
        $bankNetwork->code = "001";
        $bankNetwork->tax_codes = ["0001"];
        $bankNetwork->enabled = 1;
        $bankNetwork->prev_balance = 10000;
        $bankNetwork->balance = 10000;

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

    public function taxFilter($integration, array $packages){
        
        foreach ($integration->bankNetwork as $bank) {// 2^2*2^3
            foreach ($bank->tax_codes as $code) {// 2^3*2^4
                foreach ($packages as $package) {// 2^4*2^5
                    foreach ($package->taxes as $tax) {// 2^5*2^6
                        if ($code == $tax->code) {
                            $this->taxBillings($bank->uid, $tax->amount);
                        };
                    }
                }
                
            }
        }
        
    }
    
}