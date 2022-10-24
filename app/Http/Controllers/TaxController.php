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
    public function store(){
        
        $tax = new Tax();
    
        $tax->uid = Uuid::uuid4();
        $tax->name = 'taxa transacao';
        $tax->code = '0001';
        $tax->description = 'description';
        $tax->amount = 500;

        $tax->save();

    }
}
    // taxes:
    // uid
    // code
    // name
    // description
    // amount