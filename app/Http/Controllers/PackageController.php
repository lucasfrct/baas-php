<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Package;
use Ramsey\Uuid\Uuid;

class PackageController extends BaseController
{
    public function store(){
        
        $package = new Package();
    
        $package->uid = Uuid::uuid4();
        $package->name = 'name';
        $package->code = 'code';
        $package->description = 'description';
        $package->category = 'category';
        $package->tax_codes = 'tax';

        $package->save();

    }
}
    // 'name',
    // 'code',
    // 'uid',
    // 'description',
    // 'category',
    // 'tax codes'