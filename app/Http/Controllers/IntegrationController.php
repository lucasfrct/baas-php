<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use Ramsey\Uuid\Uuid;
use App\Models\Integration;
use App\Types\TransactionType;

class IntegrationController extends BaseController
{
    public function seed(){
        
        $integration = new Integration();
    
        $integration->uid = Uuid::uuid4();
        $integration->code = "001";
        $integration->description = 'description';
        $integration->enabled = 1;
        $integration->network_codes = ['001'];
        $integration->type = TransactionType::CashIn;

        $integration->save();

    }

    public function showByCode(string $intCode):Integration | null {

        

        $integrationData = Integration::where("code", "=", $intCode)->first();
        if (!$integrationData) {
            return null;
        }

        // foreach ($integrationData->tax_codes as $code) {
        //     $taxData = Tax::where("code", "=", $code)->first();
        //     if (!$taxData || $taxData->enabled == 0) {
        //         continue;
        //     }

        //     $integrationData->taxes[] = $taxData;
        //     $integrationData->amount += $taxData->amount;

        // }
        return $integrationData;
    }
}