<?php

namespace App\Http\Controllers;

use App\Models\transactionsModel;
use Illuminate\Http\Request;

class dashboardController extends Controller
{
    public function index()
    {
        $distinctReasons = transactionsModel::select('reason')->distinct()->pluck('reason');
        return view('dashboard', compact('distinctReasons'));
    }

    public function filter(Request $request)
    {
        $query = transactionsModel::query();

        if ($request->has('reason') && $request->input('reason') !== 'all') {
            $query->where('reason', $request->input('reason'));
        }

        if ($request->has('start_date') && $request->input('start_date') !== '') {
            $query->whereDate('date', '>=', $request->input('start_date'));
        }

        if ($request->has('end_date') && $request->input('end_date') !== '') {
            $query->whereDate('date', '<=', $request->input('end_date'));
        }

        if ($request->input('start_date') === $request->input('end_date') && $request->input('start_date') !== '') {
            $filteredTransactions = $query->select('date', 'amount', 'reason')
                ->orderBy('date')
                ->get();

            $highestTransaction = $query->orderBy('amount', 'desc')->first();
        } else {
            $filteredTransactions = $query->selectRaw('DATE(date) as date, SUM(amount) as amount')
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $highestTransaction = $query->select('date', 'amount', 'reason')
                ->whereDate('date', '=', now()->toDateString())
                ->groupBy('date', 'amount', 'reason')
                ->orderBy('amount', 'desc')
                ->first();

        }

        $response = [
            'transactions' => $filteredTransactions,
            'highest_transaction' => $highestTransaction ? [
                'date' => $highestTransaction->date,
                'amount' => $highestTransaction->amount,
                'reason' => $highestTransaction->reason,
            ] : null,
        ];

        return response()->json($response);
    }
}
