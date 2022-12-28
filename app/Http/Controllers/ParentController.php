<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use App\Models\Parents;
use App\Shared\Str;


class ParentController extends BaseController
{
    public function seed(){
        $this->record('jumeci cred', '04732012000161', 'Jumeci Bank', '', "001", 260);
    }

    public function record(string $business, string $cnpj, string $company, string $reason_social, string $code, string $ispb){

        $parent = new Parents();

        $coreIp = Str::padCoreIp($business, $cnpj);
        $subIp = Str::padSubIp($coreIp, '00');

        $parent->core_ip = $coreIp;
        $parent->sub_ip = $subIp;
        $parent->company = $company;
        $parent->reason_social = $reason_social;
        $parent->code = $code;
        $parent->ispb = $ispb;
        $parent->document = $cnpj;
        $parent->enabled = true;

        $parent->save();
    }

    public function findDocument(): string {
        $parent = Parents::where("id", "=", 1)->first();

        return $parent->document;

    }

    public function showByCode($code)
    {
       return Parents::where("code", "=", $code)->first();
    }

    public function showByIspb($ispb)
    {
       return Parents::where("ispb", "=", $ispb)->first();
    }

    public function currentBankIspb(): int
    {
        return 360305;
    }
}
