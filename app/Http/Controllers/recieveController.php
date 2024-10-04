<?php

namespace App\Http\Controllers;

use App\Models\recieveModel;
use App\Models\User;
use Illuminate\Http\Request;

class recieveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $incomingTransactions = recieveModel::all();
        return view('recieve.index', compact('incomingTransactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('recieve.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'amount' => 'required|numeric|min:0',
                'transaction_date' => 'required|date',
            ]);

            $validatedData['transaction_id'] = 'RRX' . time() . rand(1000, 9999);

            $recieve = recieveModel::create($validatedData);

            $authuser = auth()->user();
            $user = User::where('id', $authuser->id)->first();
            $user->balance += $request->amount;
            $user->save();

            return redirect()->route('receive.index')->with('success', 'Transaction proccessed!!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'An error occurred while saving the transaction: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(recieveModel $recieveModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(recieveModel $recieveModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, recieveModel $recieveModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(recieveModel $recieveModel)
    {
        //
    }
}
