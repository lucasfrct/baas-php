<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Ramsey\Uuid\Uuid;

use App\Shared\Str;
use App\Models\User;
use App\Models\Parents;
use App\Models\BankAccount;
use App\Types\OperatorType;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BanksListController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ParentController;

class BankAccountController extends Controller
{
    public function seed(string $uuid, int $code): BankAccount
    {
        $banksList = new BanksListController;
        //$banksList->showByCompany($company);

        return $this->record($uuid, $code);
    }

    public function record(string $uuid, string $code): BankAccount
    {
        $branch = new BranchController();

        $bank_account = new BankAccount();
        $bank_account->uid = Uuid::uuid4();
        $bank_account->uuid = $uuid;
        $bank_account->code = $code;
        $bank_account->branch = $branch->getCurrent($code);
        $bank_account->operator = OperatorType::Checking;
        $bank_account->enabled = true;
        $bank_account->prev_balance = 10000;
        $bank_account->balance = 10000;

        $bank_account->save();
        $bank_account->id;
        $bank_account->number = Str::padBankAccountNumber($bank_account->id);// 0001
        $bank_account->save();  

        return $bank_account;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home', ["uuid" => "895f9fca-616f-4e3f-8af5-9b5f11d1cc41"]);
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
        $bank_account->enabled = 1;
        $bank_account->prev_balance = [];
        $bank_account->balance = [];

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

    public function showByUuid($uuid): BankAccount
    {
       return BankAccount::where("uuid", "=", $uuid)->first();
    }

    public function showByNumber($code, $branch, $number, $operator): BankAccount | null
    {
       return BankAccount::whereRaw("code = ? and branch = ? and number = ? and operator = ?", [$code, $branch, $number, $operator])->first();
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

    // funcao expiration timestamp
    // timestamp: pad 10 + data de expiração pad 10 + agencia pad 4 + conta pad 6 + documento issuer pad 14 + documento receiver pad 14 + versão pad 2
    public static function certificateMount(String $timestamp, $expiration, $branch, $number, $document_issuer, $document_receiver, $version){
        return $certificate = $timestamp . $expiration . $branch . $number . $document_issuer . $document_receiver . $version;
    }

    public static function certificateDisruption(String $certificate){

        return $certificateProperies = array(
        'timestamp' => substr($certificate, 0, 10),
        'expiration' => substr($certificate, 10, 10),
        'branch' => substr($certificate, 20, 4),
        'number' => substr($certificate, 24, 6),
        'document_issuer' => substr($certificate, 30, 14),
        'document_receiver' => substr($certificate, 44, 14),
        'version' => substr($certificate, 58, 2),
        );
        
    }

    public static function getExpirationTimestamp($timestamp, $vigor){
        return $expirationTimestamp = $timestamp + $vigor;
    }

    public function updateBalance($uuid, $balance) {

        $bankAccount = new BankAccount();
        $bankAccount->balance = $balance;
        $bankAccount->save();
    }

}