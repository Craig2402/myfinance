<?php

namespace App\Http\Controllers;

use App\Models\lendModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class lendController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lends = lendModel::all();
        return view('lend.index', compact('lends'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lend.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        DB::beginTransaction();

        try {
            // Reduce the balance of the user
            $authuser = auth()->user();
            $user = User::where('id', $authuser->id)->first();
            if ($user->balance < $validatedData['amount']) {
                throw new \Exception('Insufficient balance.');
            }
            $user->balance -= $validatedData['amount'];
            $user->save();
            
            // Create the lend record
            $lend = new lendModel();
            $lend->name = $validatedData['name'];
            $lend->amount = $validatedData['amount'];
            $lend->date = $validatedData['date'];
            $lend->save();

            DB::commit();

            return redirect()->route('lend.index')->with('success', 'Lend record created successfully.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Failed to create lend record. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(lendModel $lendModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(lendModel $lendModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, lendModel $lendModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(lendModel $lendModel)
    {
        //
    }

    /**
     * Mark a lend record as paid.
     *
     * @param  \App\Models\lendModel  $lendModel
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @routes\web.php
     * Route::patch('/lend/{lendModel}/mark-as-paid', [lendController::class, 'markAsPaid'])->name('lend.markAsPaid');
     * 
     * @resources\views\lend\index.blade.php
     * <form action="{{ route('lend.markAsPaid', $lend->id) }}" method="POST">
     *     @csrf
     *     @method('PATCH')
     *     <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
     *         Mark as Paid
     *     </button>
     * </form>
     */
    public function markAsPaid(lendModel $lendModel)
    {
        try {
            DB::beginTransaction();
            
            // Reduce the balance of the user
            $authuser = auth()->user();
            $user = User::where('id', $authuser->id)->first();
            $user->balance += $lendModel->amount;
            $user->save();

            $lendModel->is_paid = true;
            $lendModel->updated_at = now();
            $lendModel->save();

            DB::commit();

            return redirect()->route('lend.index')->with('success', 'Lend record marked as paid successfully.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Failed to mark lend record as paid. Please try again.');
        }
    }

}
