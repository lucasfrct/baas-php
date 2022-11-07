<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use Ramsey\Uuid\Uuid;
use App\Models\Integration;
use App\Models\BanksNetwork;
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

        $banksNetworkCodes = [];

        foreach ($integrationData->network_codes as $code) {
            $banksNetworkData = BanksNetwork::where("code", "=", $code)->first();
            if (!$banksNetworkData || $banksNetworkData->enabled == 0) {
                continue;
            }

            $banksNetworkCodes[] = $code;
        }
        $integrationData->banks = $banksNetworkCodes;
        dd("funfou", $integrationData);

        return $integrationData;
    }
}