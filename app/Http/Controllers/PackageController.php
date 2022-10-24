<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Package;
use App\Http\Controllers\TaxController;
use Ramsey\Uuid\Uuid;

class PackageController extends BaseController
{
    public function store(){
        
        $package = new Package();
    
        $package->uid = Uuid::uuid4();
        $package->name = 'transacoes cashin';
        $package->code = '0001';
        $package->description = 'description';
        $package->category = 'cashin';
        $package->tax_codes = ['0001'];

        $package->save();

    }

    public function showByCode(string $code):Package{
        $packageData = Package::where("tax_codes", "=", $code)->first();

        foreach ($packageData->tax_codes as &$code) {
            $taxData = TaxController::where("code", "=", $code)->first();

            $packageData->taxes[] = $taxData;
            $packageData->amount += $taxData->amount;

        }
        return $packageData;
    }
}
    // 'name',
    // 'code',
    // 'uid',
    // 'description',
    // 'category',
    // 'tax codes'