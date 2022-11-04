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
    public function seed(string $uuid): int{

        $account = new Account();
        $account->uuid = $uuid;
        $account->rg = '12.760.364-5';
        $account->birthday = new \DateTime();
        $account->gender = GenderType::Male;
        $account->certificate = 'lucasfeio';
        $account->enabled = 1;
        $account->permitions = [];
        $account->without_permitions = [];
        $account->packages = ['001'];
        $account->integrations = [];

        $account->save();

        return $account->id;
    }

    public function store(){

        $account = new Account();
        $account->uuid = '';
        $account->rg = '12.760.364-5';
        $account->birthday = new \DateTime();
        $account->gender = GenderType::Male;
        $account->certificate = 'lucasfeio';
        $account->permitions = [];
        $account->without_permitions = [];

        $account->save();
    }

    public function insertCertificate(int $id, string $certificate){
        $account = Account::find($id);
        $account->certificate = $certificate;
        $account->save();
    }
}