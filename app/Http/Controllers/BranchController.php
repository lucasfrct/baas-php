<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class BranchController extends BaseController
{
    public function getCurrent(int $parent_code) { 
        return ["001", "002", "003", "004", "005", "006", "007"][0];
    }
    
}