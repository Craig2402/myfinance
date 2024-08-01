<?php

namespace App\Http\Controllers;

use App\Models\savingsModel;
use App\Models\SavingsPayment;
use App\Models\SavingsTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class savingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $savingsTargets = SavingsTarget::all();
        return view('savings.index', compact('savingsTargets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('savings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validatedData = $request->validate([
                'target_amount' => 'required|numeric|min:0',
                'description' => 'nullable|string',
                'target_date' => 'nullable|date',
            ]);

            $savingsTarget = SavingsTarget::create([
                'target_amount' => $validatedData['target_amount'],
                'description' => $validatedData['description'],
                'target_date' => $validatedData['target_date'],
            ]);

            DB::commit();

            return redirect()->route('savings.index')->with('success', 'Savings target created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occurred while creating the savings target.');
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function savingsstore(Request $request, SavingsTarget $SavingsTarget)
    {
        $validatedData = $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
        ]);

        DB::beginTransaction();

        try {
            $savingsPayment = SavingsPayment::create([
                'savings_target_id' => $SavingsTarget->id,
                'amount' => $validatedData['amount'],
                'payment_date' => $validatedData['payment_date'],
            ]);

            // Check if the target has been reached
            $totalPayments = SavingsPayment::where('savings_target_id', $SavingsTarget->id)->sum('amount');
            
            if ($totalPayments > $SavingsTarget->target_amount) {
                return redirect()->back()->with('error', 'Total payments exceed the target amount.');
            } elseif ($totalPayments == $SavingsTarget->target_amount) {
                $SavingsTarget->update(['is_achieved' => true]);
            }

            DB::commit();

            return redirect()->route('savings.show', $SavingsTarget)->with('success', 'Savings record saved successfully.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occurred while saving the record.');
        }    }

    /**
     * Display the specified resource.
     */
    public function show(SavingsTarget $SavingsTarget)
    {
        $savingsPayments = SavingsPayment::where('savings_target_id', $SavingsTarget->id)->orderBy('payment_date', 'desc')->get();
        return view('savings.show', compact('SavingsTarget', 'savingsPayments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function save(SavingsTarget $SavingsTarget)
    {
        return view('savings.save', compact('SavingsTarget'));
    }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, savingsModel $savingsModel)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(savingsModel $savingsModel)
    // {
    //     //
    // }
}
