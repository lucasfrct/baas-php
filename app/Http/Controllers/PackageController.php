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
    public function store(string $name, $code){
        
        $package = new Package();
    
        $package->uid = Uuid::uuid4();
        $package->name = $name;
        $package->code = $code;
        $package->description = 'description';
        $package->category = 'cashin';
        $package->tax_codes = ['0001'];

        $package->save();

    }

    public function showByCode(string $pkgCode):Package{
        $packageData = Package::where("code", "=", $pkgCode)->first();

        foreach ($packageData->tax_codes as &$code) {
            $taxData = TaxController::where("code", "=", $code)->first();

            $packageData->taxes[] = $taxData;
            $packageData->amount += $taxData->amount;

        }
        return $packageData;
    }

    public function addTaxCode(string $pkgCode, $taxCode){
        $packageData = Package::where("code", "=", $pkgCode)->first();
        $packageData->tax_codes[] = $taxCode;
    }
}
    // 'name',
    // 'code',
    // 'uid',
    // 'description',
    // 'category',
    // 'tax codes'