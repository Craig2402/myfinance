<?php

namespace App\Http\Controllers;

use App\Models\transactionsModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class transactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = transactionsModel::all();
        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('transactions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'transaction_type' => 'nullable',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'reason' => 'required|in:lunch,transport,snacks,Airtime-and-Bundles,tcost,other-business',
        ]);

        DB::beginTransaction();

        try {
            // Reduce the balance of the user
            $authuser = auth()->user();
            $user = User::where('id', $authuser->id)->first();
            $tcost = $this->calculateTransactionCost($request->amount, $request->transaction_type);
            $totalAmount = $request->amount + $tcost;
            if ($user->balance < $totalAmount) {
                throw new \Exception('Insufficient balance.');
            }
            $user->balance -= $request->amount;
            $user->save();

            $transaction = new transactionsModel();
            $transaction->transaction_id = 'TRX' . time() . rand(1000, 9999);
            $transaction->amount = $request->amount;
            $transaction->date = $request->date;
            $transaction->reason = $request->reason;
            $transaction->save();

            if ($tcost > 0) {
                $tcostTransaction = new transactionsModel();
                $tcostTransaction->transaction_id = 'TCOST' . time() . rand(1000, 9999);
                $tcostTransaction->amount = $tcost;
                $tcostTransaction->date = $request->date;
                $tcostTransaction->reason = 'tcost';
                $tcostTransaction->save();
            }

            DB::commit();

            return redirect()->route('transactions.index')->with('success', 'Transaction created successfully.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Failed to create transaction record. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(transactionsModel $transactionsModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(transactionsModel $transactionsModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, transactionsModel $transactionsModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(transactionsModel $transactionsModel)
    {
        //
    }

    private function calculateTransactionCost($amount, $transactiontype)
    {
        if ($transactiontype == "SM") {
            if ($amount <= 100) {
                return 0;
            } elseif ($amount >= 101 && $amount <= 500) {
                return 7;
            } elseif ($amount >= 501 && $amount <= 1000) {
                return 13;
            } elseif ($amount >= 1001 && $amount <= 1500) {
                return 23;
            } elseif ($amount >= 1501 && $amount <= 2500) {
                return 33;
            } elseif ($amount >= 2501 && $amount <= 3500) {
                return 53;
            } elseif ($amount >= 3501 && $amount <= 5000) {
                return 57;
            } elseif ($amount >= 5001 && $amount <= 7500) {
                return 78;
            } elseif ($amount >= 7501 && $amount <= 10000) {
                return 90;
            } elseif ($amount >= 10001 && $amount <= 15000) {
                return 100;
            } elseif ($amount >= 15001 && $amount <= 20000) {
                return 105;
            } elseif ($amount >= 20001 && $amount <= 35000) {
                return 108;
            } elseif ($amount >= 35001 && $amount <= 50000) {
                return 108;
            } elseif ($amount >= 50001 && $amount <= 250000) {
                return 108;
            } else {
                return 0;
            }
        } elseif ($transactiontype == "PB") {
            if ($amount <= 50) {
                return 0;
            } elseif ($amount >= 51 && $amount <= 100) {
                return 49;
            } elseif ($amount >= 101 && $amount <= 500) {
                return 7;
            } elseif ($amount >= 501 && $amount <= 1000) {
                return 13;
            } elseif ($amount >= 1001 && $amount <= 1500) {
                return 23;
            } elseif ($amount >= 1501 && $amount <= 2500) {
                return 33;
            } elseif ($amount >= 2501 && $amount <= 3500) {
                return 53;
            } elseif ($amount >= 3501 && $amount <= 5000) {
                return 57;
            } elseif ($amount >= 5001 && $amount <= 7500) {
                return 78;
            } elseif ($amount >= 7501 && $amount <= 10000) {
                return 90;
            } elseif ($amount >= 10001 && $amount <= 15000) {
                return 100;
            } elseif ($amount >= 15001 && $amount <= 20000) {
                return 105;
            } elseif ($amount >= 20001 && $amount <= 35000) {
                return 108;
            } elseif ($amount >= 35001 && $amount <= 50000) {
                return 108;
            } elseif ($amount >= 50001 && $amount <= 250000) {
                return 108;
            } else {
                return 0;
            }
        }
    }
}
