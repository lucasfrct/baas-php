<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Tax;
use Ramsey\Uuid\Uuid;

class TaxController extends BaseController
{
    public function seed(){

        $this->record('taxa transacao', '001', 'description', 500);
    }

    public function record(string $name, string $code, string $description, int $amount){
        
        $tax = new Tax();
    
        $tax->uid = Uuid::uuid4();
        $tax->name = $name;
        $tax->code = $code;
        $tax->description = $description;
        $tax->enabled = TRUE;
        $tax->amount = $amount;

        $tax->save();

    }
}
    // taxes:
    // uid
    // code
    // name
    // description
    // amount