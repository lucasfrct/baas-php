<?php

namespace App\Http\Controllers;
use App\Http\Controllers\BanksListController;
use App\Http\Controllers\BankNetworkController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;


use Illuminate\Routing\Controller as BaseController;

class AdminController extends BaseController
{
    public function index(Request $request) {
        $banksListController = new BanksListController();
        $bankNetworkController = new BankNetworkController();
        $transactionController = new TransactionController();

        $params = $request->all();
        if (isset($params['ispb'])) {
            $ispb = $params['ispb'];
            $page = $params['page'] ?? 1;
            $perpage = $params['perpage'] ?? 30;
            $bankNetwork = $bankNetworkController->showByIspb($ispb);
            $transactions = $transactionController->showPaginationByIspb($bankNetwork->ispb, $page, $perpage);
            dd($transactions);
        }

        // $banksListController->seed();
        $banksList = $banksListController->list();

        return view('admin', ['banksList' => $banksList]);
    }
}
