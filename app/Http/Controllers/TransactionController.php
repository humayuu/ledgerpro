<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Bank;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Transaction::with(['bank', 'transferToBank'])
            ->when($request->bank_id, fn ($q) => $q->where('bank_id', $request->bank_id))
            ->when($request->type, fn ($q) => $q->where('type', $request->type))
            ->latest('date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $banks = Bank::orderBy('name')->get();

        return view('transaction.index', compact('transactions', 'banks'));
    }

    public function create()
    {
        $banks = Bank::orderBy('name')->get();

        return view('transaction.create', compact('banks'));
    }

    public function store(StoreTransactionRequest $request)
    {
        $validated = $request->validated();
        $warning = $this->checkLimits($validated);

        Transaction::create($validated);

        return redirect()->back()->with([
            'success' => 'Transaction Created Successfully',
            'warning' => $warning,
        ]);
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['bank', 'transferToBank']);

        return view('transaction.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        $banks = Bank::orderBy('name')->get();

        return view('transaction.edit', compact('transaction', 'banks'));
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $validated = $request->validated();
        $warning = $this->checkLimits($validated, $transaction);

        $transaction->update($validated);

        return redirect()->route('transaction.index')->with([
            'success' => 'Transaction Updated Successfully',
            'warning' => $warning,
        ]);
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->back()->with('success', 'Transaction Deleted Successfully');
    }

    private function checkLimits(array $validated, ?Transaction $existing = null): ?string
    {
        $bank = Bank::findOrFail($validated['bank_id']);
        $date = Carbon::parse($validated['date']);
        $warning = null;

        if ($validated['type'] === 'credit') {
            $currentTotal = $bank->currentMonthCredit($date);

            if ($existing && $existing->bank_id == $bank->id && $existing->type === 'credit'
                && $existing->date->year === $date->year && $existing->date->month === $date->month) {
                $currentTotal -= (float) $existing->amount;
            }

            $newTotal = $currentTotal + $validated['amount'];

            if ($bank->monthly_limit && $newTotal > $bank->monthly_limit) {
                $warning = 'Monthly limit exceed ho gaya! Total: '.number_format($newTotal, 2).' / Limit: '.number_format($bank->monthly_limit, 2);
            }
        }

        if ($validated['type'] === 'cash_withdrawal') {
            $currentTotal = $bank->currentWeekCashWithdrawal($date);

            if ($existing && $existing->bank_id == $bank->id && $existing->type === 'cash_withdrawal'
                && $existing->date->between($date->copy()->startOfWeek(), $date->copy()->endOfWeek())) {
                $currentTotal -= (float) $existing->amount;
            }

            $newTotal = $currentTotal + $validated['amount'];

            if ($bank->weekly_cash_limit && $newTotal > $bank->weekly_cash_limit) {
                $warning = 'Weekly cash withdrawal limit exceed ho gaya! Total: '.number_format($newTotal, 2).' / Limit: '.number_format($bank->weekly_cash_limit, 2);
            }
        }

        return $warning;
    }
}
