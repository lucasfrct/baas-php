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

use App\Http\Controllers\Controller;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\AccountController;
use App\Models\BankAccount;
use App\Models\User;
use App\Models\Parents;
use App\Types\OperatorType;

class BankAccountController extends Controller
{
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

    public static function autoInit(string $user_uuid, $document){
        // 1600000000ms
        $date = new \DateTime();
        $uuid = Uuid::uuid4();
        $account = new AccountController();
        $parent = new Parents;

        $branch = BranchController::getCurrent();

        $bank_account = new BankAccount();
        $bank_account->uuid = Uuid::uuid4();
        $bank_account->number = "XXX000";
        $bank_account->branch = BranchController::getCurrent();
        $bank_account->operator = OperatorType::Checking;
        $bank_account->user_uuid = $user_uuid;

        $parentData = $parent::where("document", "=", "01234567890001")->first();
        $issuer = $parentData->document;
        
        $certificate = $account->generateCertificate($bank_account->branch, $bank_account->number, $issuer, $document);
        dd($certificate);
        // list() = BankAccountController::certificateDisruption($toekn);
        
        // dd(list());
        $bank_account->save();
        $bank_account->id;
        $bank_account->number = str_pad($bank_account->id, 4,"0", STR_PAD_LEFT);// 0001
        $bank_account->save();
        // insert into from bank_account set (uuid, number, branch, operator, user_uuid) values(asdf, XXX000, 001, 1, kjhdfjhgalfgafljha)
        
        // funcao expiration timestamp
        // timestamp: pad 10 + data de expiração pad 10 + agencia pad 4 + conta pad 6 + documento issuer pad 14 + documento receiver pad 14 + versão pad 2
        
        

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
}
