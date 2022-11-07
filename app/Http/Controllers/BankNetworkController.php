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
}