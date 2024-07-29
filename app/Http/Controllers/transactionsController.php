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
            'transaction_id' => 'required|unique:transactions_models',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'reason' => 'required|in:lunch,transport,snacks',
        ]);

        DB::beginTransaction();

        try {
            // Reduce the balance of the user
            $authuser = auth()->user();
            $user = User::where('id', $authuser->id)->first();
            if ($user->balance < $request->amount) {
                throw new \Exception('Insufficient balance.');
            }
            $user->balance -= $request->amount;
            $user->save();

            $transaction = new transactionsModel();
            $transaction->transaction_id = $request->transaction_id;
            $transaction->amount = $request->amount;
            $transaction->date = $request->date;
            $transaction->reason = $request->reason;
            $transaction->save();

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
}
