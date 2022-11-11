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
    public function seed(): BanksList
    {
        return $this->record('NU PAGAMENTOS - IP', 'NUBANK', '24410913000144', "260", 18236120);
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
    public function show()
    {

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

    public function showByIspb(string $ispb): BanksList
    {
        return BanksList::where("ispb", "=", $ispb)->first();
    }
}