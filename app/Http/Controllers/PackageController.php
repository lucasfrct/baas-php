<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use App\Models\Package;
use App\Models\Tax;
use App\Types\TransactionType;
use Ramsey\Uuid\Uuid;

class PackageController extends BaseController
{
    public function seed(){

        $this->record('pkg01', '001', 'description', TransactionType::CashIn, ['001']);
    }

    public function record(string $name, string $code, string $description, TransactionType $category, array $tax_codes){
        
        $package = new Package();
    
        $package->uid = Uuid::uuid4();
        $package->name = $name;
        $package->code = $code;
        $package->description = $description;
        $package->category = $category;
        $package->enabled = TRUE;
        $package->tax_codes = $tax_codes;

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