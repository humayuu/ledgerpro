<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Bank;
use App\Models\Transaction;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::with(['bank', 'transferToBank'])->paginate(10);

        return view('transaction.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $banks = Bank::all();
        return view('transaction.create', compact('banks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $validated = $request->validated();
        $bank = Bank::findOrFail($validated['bank_id']);
        $warning = null;

        if ($validated['type'] === 'credit') {
            $currentTotal = $bank->monthlyCreditTotal(
                Carbon::parse($validated['date'])->month,
                Carbon::parse($validated['date'])->year
            );
            $newTotal = $currentTotal + $validated['amount'];

            if ($bank->monthly_limit && $newTotal > $bank->monthly_limit) {
                $warning = "Monthly limit exceed ho gaya! Total: $newTotal / Limit: {$bank->monthly_limit}";
            }
        }

        if ($validated['type'] === 'cash_withdrawal') {
            $currentTotal = $bank->weeklyCashWithdrawalTotal($validated['date']);
            $newTotal = $currentTotal + $validated['amount'];

            if ($bank->weekly_cash_limit && $newTotal > $bank->weekly_cash_limit) {
                $warning = "Weekly cash withdrawal limit exceed ho gaya! Total: $newTotal / Limit: {$bank->weekly_cash_limit}";
            }
        }

        Transaction::create($validated);

        return redirect()->back()->with([
            'success' => 'Transaction Created Successfully',
            'warning' => $warning,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        return view('transaction.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        $banks = Bank::all();
        return view('transaction.edit', compact('transaction', 'banks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $validated = $request->validated();
        $bank = Bank::findOrFail($validated['bank_id']);
        $warning = null;

        if ($validated['type'] === 'credit') {
            $currentTotal = $bank->monthlyCreditTotal(
                Carbon::parse($validated['date'])->month,
                Carbon::parse($validated['date'])->year
            );
            if ($transaction->bank_id == $bank->id && $transaction->type === 'credit') {
                $currentTotal -= $transaction->amount;
            }
            $newTotal = $currentTotal + $validated['amount'];

            if ($bank->monthly_limit && $newTotal > $bank->monthly_limit) {
                $warning = "Monthly limit exceed ho gaya! Total: $newTotal / Limit: {$bank->monthly_limit}";
            }
        }

        if ($validated['type'] === 'cash_withdrawal') {
            $currentTotal = $bank->weeklyCashWithdrawalTotal($validated['date']);
            if ($transaction->bank_id == $bank->id && $transaction->type === 'cash_withdrawal') {
                $currentTotal -= $transaction->amount;
            }
            $newTotal = $currentTotal + $validated['amount'];

            if ($bank->weekly_cash_limit && $newTotal > $bank->weekly_cash_limit) {
                $warning = "Weekly cash withdrawal limit exceed ho gaya! Total: $newTotal / Limit: {$bank->weekly_cash_limit}";
            }
        }

        $transaction->update($validated);

        return redirect()->back()->with([
            'success' => 'Transaction Updated Successfully',
            'warning' => $warning,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->back()->with('success', 'Transaction Deleted Successfully');
    }
}
