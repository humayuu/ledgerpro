@extends('layout')
@section('content')
    <h1 class="text-center text-primary fw-bold mb-4">Transaction Details</h1>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-bold">{{ $transaction->typeLabel() }}</span>
            <div>
                <a href="{{ route('transaction.edit', $transaction) }}" class="btn btn-sm btn-dark">
                    <i class="fa-solid fa-pen me-1"></i>Edit
                </a>
                <a href="{{ route('transaction.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fa-solid fa-arrow-left me-1"></i>Back
                </a>
            </div>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between">
                <span class="fw-bold">Bank</span>
                <span>{{ $transaction->bank->name }} ({{ $transaction->bank->account_title }})</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <span class="fw-bold">Date</span>
                <span>{{ $transaction->date->format('d M Y') }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <span class="fw-bold">Type</span>
                <span>
                    @if ($transaction->type === 'credit')
                        <span class="badge bg-success">Credit</span>
                    @elseif ($transaction->type === 'cash_withdrawal')
                        <span class="badge bg-warning text-dark">Cash Withdrawal</span>
                    @else
                        <span class="badge bg-info text-dark">Bank Transfer</span>
                    @endif
                </span>
            </li>
            @if ($transaction->type === 'credit')
                <li class="list-group-item d-flex justify-content-between">
                    <span class="fw-bold">Party Name</span>
                    <span>{{ $transaction->party_name ?? '-' }}</span>
                </li>
            @endif
            @if ($transaction->type === 'bank_transfer')
                <li class="list-group-item d-flex justify-content-between">
                    <span class="fw-bold">Transfer To</span>
                    <span>{{ $transaction->transferToBank?->name ?? '-' }} ({{ $transaction->transferToBank?->account_title ?? '' }})</span>
                </li>
            @endif
            <li class="list-group-item d-flex justify-content-between">
                <span class="fw-bold">Amount</span>
                <span class="fs-5 fw-bold text-primary">Rs. {{ number_format($transaction->amount, 2) }}</span>
            </li>
            @if ($transaction->notes)
                <li class="list-group-item d-flex justify-content-between">
                    <span class="fw-bold">Notes</span>
                    <span>{{ $transaction->notes }}</span>
                </li>
            @endif
        </ul>
    </div>
@endsection
