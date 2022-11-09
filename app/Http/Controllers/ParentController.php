<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use App\Models\Parents;
use App\Shared\Str;


class ParentController extends BaseController
{
    public function seed(){
        $this->record('jumeci cred', '04732012000161');
    }

    public function record(string $business, string $cnpj){

        $parent = new Parents();

        $coreIp = Str::padCoreIp($business, $cnpj);
        $subIp = Str::padSubIp($coreIp, '00');

        $parent->core_ip = $coreIp;
        $parent->sub_ip = $subIp;
        $parent->document = $cnpj;
        $parent->enabled = true;

        $parent->save();
    }

    public function findDocument(): string {
        $parent = Parents::where("id", "=", 1)->first();

        return $parent->document;

    }
}
