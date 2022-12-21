<?php

namespace App\Http\Controllers;
use App\Http\Controllers\BanksListController;


use Illuminate\Routing\Controller as BaseController;

class AdminController extends BaseController
{
    public function index() {
        $banksListController = new BanksListController();

        // $banksListController->seed();
        $banksList = $banksListController->list();

        return view('admin', ['banksList' => $banksList]);
    }
}
