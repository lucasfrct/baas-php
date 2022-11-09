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

        $this->record("001", 'description', ['001'], TransactionType::CashIn);
    }

    public function record(string $code, string $description, array $network_codes, TransactionType $type){
        
        $integration = new Integration();
    
        $integration->uid = Uuid::uuid4();
        $integration->code = $code;
        $integration->description = $description;
        $integration->enabled = TRUE;
        $integration->network_codes = $network_codes;
        $integration->type = $type;

        $integration->save();
    }

    public function showByCode(string $intCode):Integration | null {        

        $integrationData = Integration::where("code", "=", $intCode)->first();
        if (!$integrationData) {
            return null;
        };

        $integrationData->bankNetwork = [];

        foreach ($integrationData->network_codes as $code) {
            $banksNetworkData = BanksNetwork::where("code", "=", $code)->first();
            if (!$banksNetworkData || $banksNetworkData->enabled == FALSE) {
                continue;
            }

            $integrationData->bankNetwork[] = $banksNetworkData;
        };

        return $integrationData;
    }
}