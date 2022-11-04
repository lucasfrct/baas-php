<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use Ramsey\Uuid\Uuid;
use App\Models\BankNetwork;
use App\Types\OperatorType;

class BankNetworkController extends BaseController
{
    public function seed():BankAccount{

        $branch = new BranchController();

        $bankNetwork = new BankNetwork();
        $bankNetwork->uid = Uuid::uuid4();
        $bankNetwork->number = "XXX000";
        $bankNetwork->branch = $branch->getCurrent();
        $bankNetwork->operator = OperatorType::Checking;
        $bankNetwork->bank_ispb = 1;
        $bankNetwork->code = "";
        $bankNetwork->enabled = 1;
        $bankNetwork->prev_balance = 10000;
        $bankNetwork->balance = 10000;

        $bankNetwork->save();        

        return $bankNetwork;
    }
}