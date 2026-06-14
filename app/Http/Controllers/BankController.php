<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBankRequest;
use App\Http\Requests\UpdateBankRequest;
use App\Models\Bank;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banks = Bank::with(['transactions'])->paginate(10);
        return view('bank.index', compact('banks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bank.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBankRequest $request)
    {
        $validated = $request->validated();

        Bank::create($validated);

        return redirect()->back()->with('success', 'Bank Details Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bank $bank)
    {
        $bank->load('transactions');
        return view('bank.show', compact('bank'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bank $bank)
    {
        return view('bank.edit', compact('bank'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBankRequest $request, Bank $bank)
    {
        $validated = $request->validated();

        $bank->update($validated);

        return redirect()->back()->with('success', 'Bank Details Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bank $bank)
    {
        if ($bank->transactions()->count() > 0) {
            return redirect()->back()->with('error', 'Please clear transactions before deleting this bank.');
        }

        $bank->delete();

        return redirect()->back()->with('success', 'Bank Details Deleted Successfully');
    }
}
