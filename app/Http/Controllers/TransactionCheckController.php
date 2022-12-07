<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\BanksListController;
use App\Models\BankAccount;
use App\Models\User;
use App\Types\OperatorType;
use App\Types\TransactionType;

class TransactionCheckController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bankAccountController = new BankAccountController();
        $banksListController = new BanksListController();

        $user = Auth::user();
        $bankAccount = $bankAccountController->showByUuid($user->uuid);
        $bank = $banksListController->showByCode($bankAccount->code);
        $banksList = $banksListController->list();

        return view('transactionCheck', ["user" => $user, "bankAccount" => $bankAccount, "bank" => $bank, "banksList" => $banksList]);
    }
}