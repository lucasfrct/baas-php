<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use App\Models\Account;
use App\Types\GenderType;

class AccountController extends BaseController
{
    public function seed(string $uuid): int{        

        return $this->record($uuid, '12.760.364-5', new \DateTime(), GenderType::Male, ['001'], ['001']);
    }

    public function record(string $uuid, string $rg, $birthday, GenderType $gender, array $packages, array $integrations): int{

        $account = new Account();
        $account->uuid = $uuid;
        $account->rg = $rg;
        $account->birthday = $birthday;
        $account->gender = $gender;
        $account->permitions = [];
        $account->without_permitions = [];
        $account->packages = $packages;
        $account->integrations = $integrations;
        $account->enabled = 1;

        $account->save();
        return $account->id;
    }

    public function insertCertificate(int $id, string $certificate){
        $account = Account::find($id);
        $account->certificate = $certificate;
        $account->save();
    }
}