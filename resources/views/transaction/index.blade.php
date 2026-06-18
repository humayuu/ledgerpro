@extends('layout')
@section('content')
    <h1 class="text-center text-primary fw-bold">Transactions</h1>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-1"></i> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <form method="GET" action="{{ route('transaction.index') }}" class="d-flex gap-2 flex-wrap">
            <select name="bank_id" class="form-select form-select-sm" style="width: auto;">
                <option value="">All Banks</option>
                @foreach ($banks as $bank)
                    <option value="{{ $bank->id }}" {{ request('bank_id') == $bank->id ? 'selected' : '' }}>
                        {{ $bank->name }} ({{ $bank->account_title }})
                    </option>
                @endforeach
            </select>
            <select name="type" class="form-select form-select-sm" style="width: auto;">
                <option value="">All Types</option>
                <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Credit</option>
                <option value="cash_withdrawal" {{ request('type') == 'cash_withdrawal' ? 'selected' : '' }}>Cash Withdrawal</option>
                <option value="bank_transfer" {{ request('type') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
            </select>
            <button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
            @if (request('bank_id') || request('type'))
                <a href="{{ route('transaction.index') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
            @endif
        </form>
        <a href="{{ route('transaction.create') }}" class="btn btn-primary">Create Transaction</a>
    </div>

    <table class="table table-hover">
        @if ($transactions->count() > 0)
            <thead class="table-dark">
                <tr class="text-center">
                    <th>#</th>
                    <th>Bank</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Party Name</th>
                    <th>Amount</th>
                    <th>Transfer To</th>
                    <th>Handle</th>
                </tr>
            </thead>
        @endif
        <tbody>
            @forelse ($transactions as $transaction)
                <tr class="text-center">
                    <th scope="row">{{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}</th>
                    <td>{{ $transaction->bank->name }}</td>
                    <td>{{ $transaction->date->format('d M Y') }}</td>
                    <td>
                        @if ($transaction->type === 'credit')
                            <span class="badge bg-success">Credit</span>
                        @elseif ($transaction->type === 'cash_withdrawal')
                            <span class="badge bg-warning text-dark">Cash</span>
                        @else
                            <span class="badge bg-info text-dark">Transfer</span>
                        @endif
                    </td>
                    <td>{{ $transaction->party_name ?? '-' }}</td>
                    <td>{{ number_format($transaction->amount, 2) }}</td>
                    <td>{{ $transaction->transferToBank?->name ?? '-' }}</td>
                    <td class="d-flex justify-content-center gap-2">
                        <a class="btn btn-primary btn-sm" href="{{ route('transaction.show', $transaction) }}"><i class="fa-solid fa-eye"></i></a>
                        <a class="btn btn-dark btn-sm" href="{{ route('transaction.edit', $transaction) }}"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('transaction.destroy', $transaction) }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="if(confirm('Are you sure?')) this.closest('form').submit()"
                                class="btn btn-danger btn-sm">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">
                        <div class="alert alert-info mb-0">No Record Found!</div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $transactions->links() }}
@endsection
