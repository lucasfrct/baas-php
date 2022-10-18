<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Account;
use App\Types\GenderType;
use App\Shared\Str;

class AccountController extends BaseController
{
    public function store(){

        $account = new Account();
        $account->uuid = '';
        $account->rg = '12.760.364-5';
        $account->birthday = new \DateTime();
        $account->gender = GenderType::Male;
        $account->certificate = 'lucasfeio';
        $account->permitions = ['1','2','3'];
        $account->without_permitions = ['1','2','3'];

        $account->save();
    }

    public function init(string $uuid){

        $account = new Account();
        $account->uuid = $uuid;
        $account->permitions = [];
        $account->without_permitions = [];

        $account->save();
    }
}