<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use App\Models\Account;
use App\Types\GenderType;
use DateTime;

class AccountController extends BaseController
{
    public function seed(string $uuid): Account
    {
        $birthday = new DateTime();
        return $this->record($uuid, '12.760.364-5', $birthday, GenderType::Male, ['001'], ['001']);
    }

    public function record(string $uuid, string $rg, DateTime $birthday, GenderType $gender, array $packages, array $integrations): Account
    {
        $account = new Account();
        $account->uuid = $uuid;
        $account->rg = $rg;
        $account->birthday = $birthday;
        $account->gender = $gender;
        $account->permitions = [];
        $account->without_permitions = [];
        $account->packages = $packages;
        $account->integrations = $integrations;
        $account->enabled = true;

        $account->save();
        return $account;
    }

    public function insertCertificateByUuid(string $uuid, string $certificate): Account
    {
        $account = Account::where("uuid", "=", $uuid)->first();
        $account->certificate = $certificate;
        $account->save();

        return $account;
    }

    public function showByUuid($uuid): Account 
    {
       return Account::where("uuid", "=", $uuid)->first();
    }
}