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

class DashboardController extends Controller
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

        return view('dashboard', ["user" => $user, "bankAccount" => $bankAccount, "bank" => $bank, "banksList" => $banksList]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('home');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $form = $request->all();

        $bank_account = new BankAccount();
        $bank_account->uuid = Uuid::uuid4();
        $bank_account->number = $form['number'];
        $bank_account->branch = $form['branch'];
        $bank_account->operator = $form['operator'];

        $bank_account->save();

        return view("home");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bankAccountData = BankAccount::where("uuid", "=", $id)->first();
        // dd($bankAccountData->uuid, $bankAccountData->number);
        $bankAccount = [
            'number' => $bankAccountData->number,
            'branch' => $bankAccountData->branch,
            'operator' => $bankAccountData->operator
        ];
        return view("home", ["bankAccount" => $bankAccount]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function applyingTransaction(Request $request) 
    {
        $transactionController = new TransactionController;

        $form = $request->all();
        
        if ($form["transaction_type"] == TransactionType::CashOut->value) {
            $type = TransactionType::CashOut;
        }

        if (intval($form["receipient_bank_operator"]) == OperatorType::Checking->value) {
            $operatorType = OperatorType::Checking;
        }
        
        $transactionController->operate(
            $form["payer_uuid"],
            $form["amount"],
            $type,
            $form["payer_bank_ispb"],
            $form["receipient_bank_ispb"],
            $form["receipient_bank_branch"],
            $form["receipient_bank_number"],
            $operatorType
        );

        return redirect()->intended('dashboard');
    }
}
