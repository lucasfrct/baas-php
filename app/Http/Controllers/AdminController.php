<?php

namespace App\Http\Controllers;
use App\Http\Controllers\BanksListController;
use App\Http\Controllers\BankNetworkController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BalanceController;
use Illuminate\Http\Request;


use Illuminate\Routing\Controller as BaseController;

class AdminController extends BaseController
{
    public function index(Request $request) {
        $banksListController = new BanksListController();
        $bankNetworkController = new BankNetworkController();
        $transactionController = new TransactionController();
        $balanceController = new BalanceController();

        $total = 0;
        $balance = 0;
        $prevBalance = 0;
        $transactionsList = [];
        $params = $request->all();
        if (isset($params['ispb'])) {
            $ispb = $params['ispb'];
            $page = $params['page'] ?? 1;
            $perpage = $params['perpage'] ?? 30;
            $bankNetwork = $bankNetworkController->showByIspb($ispb);
            list($transactionsList, $total, $currentPage, $perPage, $lastPage) = $transactionController->showPaginationByIspb($bankNetwork->ispb, $page, $perpage);
            $balance = $balanceController->currentMonthByIspb($params['ispb']);
            $prevBalance = $balanceController->lastMonthByIspb($params['ispb']);
        }
        
        $transactionStatusLabel = $transactionController->statusLabel();
        $transactionTypeLabel = $transactionController->typeLabel();

        // $banksListController->seed();
        $banksList = $banksListController->list();

        return view(
            'admin', 
            ['banksList' => $banksList, 
            'balance' => $balance, 
            'prevBalance' => $prevBalance, 
            'transactionsList' => $transactionsList, 
            'transactionStatusLabel' => $transactionStatusLabel,
            'transactionTypeLabel' => $transactionTypeLabel,
            'total' => $total
        ]);
    }
}
