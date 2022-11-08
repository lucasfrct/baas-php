<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Package;
use App\Models\Tax;
use App\Http\Controllers\TaxController;
use Ramsey\Uuid\Uuid;

class PackageController extends BaseController
{
    public function seed(string $name, $code){
        
        $package = new Package();
    
        $package->uid = Uuid::uuid4();
        $package->name = $name;
        $package->code = $code;
        $package->description = 'description';
        $package->category = 'cashin';
        $package->enabled = 1;
        $package->tax_codes = ['0001'];

        $package->save();

    }

    public function showByCode(string $pkgCode):Package | null {
        $packageData = Package::where("code", "=", $pkgCode)->first();
        if (!$packageData) {
            return null;
        }

        foreach ($packageData->tax_codes as $code) {
            $taxData = Tax::where("code", "=", $code)->first();
            if (!$taxData || $taxData->enabled == FALSE) {
                continue;
            }

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