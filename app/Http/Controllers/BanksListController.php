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
use App\Models\BanksList;
use App\Types\OperatorType;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\AccountController;

class BanksListController extends Controller
{
    public function seed(string $uuid):BankAccount{
        
        $banks_list = new BankAccount();
        $banks_list->company = "73437854000103";
        $banks_list->reason_social = "73437854000103";
        $banks_list->document = "73437854000103";
        $banks_list->code = 1;
        $banks_list->ispb = 1;
        
        $banks_list->save();       
    
        return $banks_list;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        
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

    public function showByUuid($uuid)
    {
       return BankAccount::where("uuid", "=", $uuid)->first();
    }

    public function showByNumber($branch, $number, $operator): BankAccount | null
    {
       return BankAccount::whereRaw("branch = ? and number = ? and operator = ?", [$branch, $number, $operator])->first();
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

    public static function getExpirationTimestamp($timestamp, $vigor){
        return $expirationTimestamp = $timestamp + $vigor;
    }

    public function record(string $company, string $reason_social, string $document, int $code, int $ispb): BanksList
    {
        $banks_list = new BanksList();
        $banks_list->company = $company;
        $banks_list->reason_social = $reason_social;
        $banks_list->document = $document;
        $banks_list->code = $code;
        $banks_list->ispb = $ispb;

        $banks_list->save();

        return $banks_list;
    }

    public function showByCompany(string $company): BanksList
    {
        return BanksList::where("company", "=", $company)->first();
    }
}