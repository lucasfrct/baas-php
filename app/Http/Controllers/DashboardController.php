<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\BanksListController;
use App\Models\BankAccount;
use App\Types\OperatorType;
use App\Types\TransactionStatusType;
use App\Types\TransactionType;
use DateTime;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bankAccountController = new BankAccountController();
        $banksListController = new BanksListController();
        $cashOut = TransactionType::CashOut->value;

        $user = Auth::user();
        $bankAccount = $bankAccountController->showByUuid($user->uuid);
        $bank = $banksListController->showByCode($bankAccount->code);
        $banksList = $banksListController->list();

        return view('dashboard', ["user" => $user, "bankAccount" => $bankAccount, "userBank" => $bank, "banksList" => $banksList, "cashout" => $cashOut]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('home');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $form = $request->all();

        $bank_account = new BankAccount();
        $bank_account->uuid = Uuid::uuid4();
        $bank_account->number = $form['number'];
        $bank_account->branch = $form['branch'];
        $bank_account->operator = $form['operator'];

        $bank_account->save();

        return view("home");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bankAccountData = BankAccount::where("uuid", "=", $id)->first();
        // dd($bankAccountData->uuid, $bankAccountData->number);
        $bankAccount = [
            'number' => $bankAccountData->number,
            'branch' => $bankAccountData->branch,
            'operator' => $bankAccountData->operator
        ];
        return view("home", ["bankAccount" => $bankAccount]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function applyingTransaction(Request $request) 
    {
        $transactionController = new TransactionController;

        $form = $request->all();
        
        if ($form["transaction_type"] == TransactionType::CashOut->value) {
            $type = TransactionType::CashOut;
        }

        if (intval($form["receipient_bank_operator"]) == OperatorType::Checking->value) {
            $operatorType = OperatorType::Checking;
        }
        
        $transactionController->operate(
            $form["payer_uuid"],
            $form["amount"],
            $type,
            $form["payer_bank_ispb"],
            OperatorType::Checking,
            $form["receipient_bank_ispb"],
            $form["receipient_bank_branch"],
            $form["receipient_bank_number"],
            $operatorType
        );

        return redirect()->intended('dashboard');
    }

    public function transactionCheck(Request $request) {

        try {
            $request->validate(
                [
                    'payer_uuid' => ['required'],
                    'transaction_type' => ['required'],
                    'payer_bank_ispb' => ['required'],
                    'receipient_bank_ispb' => ['required'],
                    'receipient_bank_branch' => ['required'],
                    'receipient_bank_number' => ['required'],
                    'receipient_bank_operator' => ['required'],
                ],
                [
                    'receipient_bank_ispb.required' => 'O banco do rebedor é obrigatório',
                    'receipient_bank_branch.required' => 'A agencia bancaria do rebedor é obrigatória',
                    'receipient_bank_number.required' => 'A numero da conta do rebedor é obrigatório',
                    'receipient_bank_operator.required' => 'O operador da conta do rebedor é obrigatório',
                    
                ]
            );
    
            $form = $request->all();
    
            $transactionController = new TransactionController();
            $transactionType = ($form["transaction_type"] == "cashout") ? TransactionType::CashOut : TransactionType::CashIn;
            $operatorType = ($form["receipient_bank_operator"] == 1) ? OperatorType::Checking : OperatorType::Savings;

    
            list($receipientData) = $transactionController->prepare(
                $form["payer_uuid"],
                0,
                $transactionType,
                $form["payer_bank_ispb"],
                OperatorType::Checking,
                $form["receipient_bank_ispb"],
                $form["receipient_bank_branch"],
                $form["receipient_bank_number"],
                $operatorType,
            );
            
            $operatorTypeLabel = [];
            $operatorTypeLabel = array_map(function ($item){
                $name = "";
                switch ($item->name) {
                    case 'Savings':
                        $name = "Poupanca";
                        break;
                    
                    case 'Checking':
                        $name = "Corrente";
                        break;
                    
                    case 'Vgbl':
                        $name = "Plano de Previdencia";
                        break;
                            
                    default:
                        $name = $item->name;
                        break;
                }
                return array("label" => $name, "value" => $item->value);
                        
            }, OperatorType::cases());
    
            // return redirect()->back()->withErrors(['email' => 'Verifique se o email foi digitado corretamente.', 'password' => 'Verifique se a senha foi digitada corretamnete.']);
    
            $bankAccountController = new BankAccountController();
            $banksListController = new BanksListController();
    
            $user = Auth::user();
            $bankAccount = $bankAccountController->showByUuid($user->uuid);
            $bank = $banksListController->showByCode($bankAccount->code);
    
            return view('transactionCheck', [
                "user" => $user,
                "bankAccount" => $bankAccount,
                "userBank" => $bank,
                "transaction" => $form,
                "receipientData" => $receipientData,
                "operatorTypeLabel" => $operatorTypeLabel
            ]);

        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['errorDefault' => $th->getMessage()]);
        }
    }

    public function transactionResume(Request $request) {

        try {
            $request->validate(
                [
                    'payer_uuid' => ['required'],
                    'transaction_type' => ['required'],
                    'payer_bank_ispb' => ['required'],
                    'receipient_bank_ispb' => ['required'],
                    'receipient_bank_branch' => ['required'],
                    'receipient_bank_number' => ['required'],
                    'receipient_bank_operator' => ['required'],
                    'payer_operator_type' => ['required'],
                    'amount' => ['required'],
                ],
                [
                    'receipient_bank_ispb.required' => 'O banco do rebedor é obrigatório',
                    'receipient_bank_branch.required' => 'A agencia bancaria do rebedor é obrigatória',
                    'receipient_bank_number.required' => 'A numero da conta do rebedor é obrigatório',
                    'receipient_bank_operator.required' => 'O operador da conta do rebedor é obrigatório',
                    'payer_operator_type.required' => 'O operador da conta do pagador é obrigatório',
                    'amount.required' => 'O valor da transferencia é obrigatório',
                ]
            );

            $form = $request->all();

            $transactionController = new TransactionController();
            $transactionType = ($form["transaction_type"] == "cashout") ? TransactionType::CashOut : TransactionType::CashIn;
            $operatorType = ($form["receipient_bank_operator"] == 1) ? OperatorType::Checking : OperatorType::Savings;
            $payerOperatorType = ($form["payer_operator_type"] == 1) ? OperatorType::Checking : OperatorType::Savings;

            $form["amount"] = preg_replace( '/[^0-9]/', '', $form["amount"] );
    
            list(
                $receipientData,
                $receipientBankAccount,
                $receipientBank,
                $packagesAmount,
                $amountCharge
            ) = $transactionController->prepare(
                $form["payer_uuid"],
                $form["amount"],
                $transactionType,
                $form["payer_bank_ispb"],
                $payerOperatorType,
                $form["receipient_bank_ispb"],
                $form["receipient_bank_branch"],
                $form["receipient_bank_number"],
                $operatorType,
            );
            
            $bankAccountController = new BankAccountController();
            $banksListController = new BanksListController();
            
            $user = Auth::user();
            $bankAccount = $bankAccountController->showByUuid($user->uuid);
            $bank = $banksListController->showByCode($bankAccount->code);

            $dateTime = date_format(new DateTime(),"d/m/Y H:i");
            
            return view('transactionResume', 
            [
                "user" => $user,
                "bankAccount" => $bankAccount,
                "userBank" => $bank,
                "transaction" => $form,
                "dateTime" => $dateTime,
                "receipientData" => $receipientData,
                "receipientBankAccount" => $receipientBankAccount,
                "receipientBank" => $receipientBank,
                "packagesAmount" => $packagesAmount,
                "amountCharge" => $amountCharge
            ]);
            
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['errorDefault' => $th->getMessage()]);
        }
    }
    
    public function transactionApply(Request $request) {
        
        try {

            $request->validate(
                [
                    'payer_uuid' => ['required'],
                    'transaction_type' => ['required'],
                    'payer_bank_ispb' => ['required'],
                    'receipient_bank_ispb' => ['required'],
                    'receipient_bank_branch' => ['required'],
                    'receipient_bank_number' => ['required'],
                    'receipient_bank_operator' => ['required'],
                    'payer_operator_type' => ['required'],
                    'amount' => ['required'],
                ],
                [
                    'receipient_bank_ispb.required' => 'O banco do rebedor é obrigatório',
                    'receipient_bank_branch.required' => 'A agencia bancaria do rebedor é obrigatória',
                    'receipient_bank_number.required' => 'A numero da conta do rebedor é obrigatório',
                    'receipient_bank_operator.required' => 'O operador da conta do rebedor é obrigatório',
                    'payer_operator_type.required' => 'O operador da conta do pagador é obrigatório',
                    'amount.required' => 'O valor da transferencia é obrigatório',
                ]
            );

            $form = $request->all();

            $transactionController = new TransactionController();
            $transactionType = ($form["transaction_type"] == "cashout") ? TransactionType::CashOut : TransactionType::CashIn;
            $operatorType = ($form["receipient_bank_operator"] == 1) ? OperatorType::Checking : OperatorType::Savings;
            $payerOperatorType = ($form["payer_operator_type"] == 1) ? OperatorType::Checking : OperatorType::Savings;

            $form["amount"] = preg_replace( '/[^0-9]/', '', $form["amount"] );
            
            $transactionController->operate(
                $form["payer_uuid"],
                $form["amount"],
                $transactionType,
                $form["payer_bank_ispb"],
                $payerOperatorType,
                $form["receipient_bank_ispb"],
                $form["receipient_bank_branch"],
                $form["receipient_bank_number"],
                $operatorType,
            );

            return redirect()->intended('dashboard'); // ToDo tirar depois de pronto

        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['errorDefault' => $th->getMessage()]);
        }
    }

    public function bankStatement(Request $request) {

        $form = $request->all();

        $bankAccountController = new BankAccountController();
        $transactionController = new TransactionController();
        $banksListController = new BanksListController();
        $cashOut = TransactionType::CashOut->value;

        $user = Auth::user();
        $bankAccount = $bankAccountController->showByUuid($user->uuid);
        $bank = $banksListController->showByCode($bankAccount->code);
        $banksList = $banksListController->list();

        $isEmpty = (count($form) == 0) ? true : false;
        
        if ($isEmpty) {
            $endDate = date("Y-m-d");
            $startDate = date_create($endDate);
            date_sub($startDate, date_interval_create_from_date_string("30 days"));
            $startDate = date_format($startDate,"Y-m-d");
            $form["start_date"] = $startDate;
            $form["end_date"] = $endDate;
        };

        $transactionsList = $transactionController->showBetweenDates("uuid", $user->uuid, $form["start_date"], $form["end_date"]);

        $transactionStatusLabel = $transactionController->statusLabel();
        $transactionTypeLabel = $transactionController->typeLabel();

        return view(
            'bankStatement', 
            [
                "user" => $user, 
                "bankAccount" => $bankAccount, 
                "userBank" => $bank, 
                "banksList" => $banksList, 
                "cashout" => $cashOut, 
                "transactionsList" => $transactionsList,
                "form" => $form,
                "transactionStatusLabel" => $transactionStatusLabel,
                "transactionTypeLabel" => $transactionTypeLabel
            ]
        );
    }

    public function bankStatementReport() {

        $bankAccountController = new BankAccountController();
        $transactionController = new TransactionController();
        $banksListController = new BanksListController();
        $cashOut = TransactionType::CashOut->value;
        
        $user = Auth::user();
        $bankAccount = $bankAccountController->showByUuid($user->uuid);
        $bank = $banksListController->showByCode($bankAccount->code);
        $banksList = $banksListController->list();
        
        // $transactionController->showBalanceBetweenDates($key, $value, $from, $until);

        return view('bankStatement', ["user" => $user, "bankAccount" => $bankAccount, "userBank" => $bank, "banksList" => $banksList, "cashout" => $cashOut]);
    }
}
