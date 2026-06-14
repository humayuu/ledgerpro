@extends('layout')
@section('content')
    <h1 class="text-center text-primary fw-bold">Transactions Details</h1>

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

    <div class="d-flex justify-content-end">
        <a href="{{ route('transaction.create') }}" class="btn btn-primary m-3">Create Transaction</a>
    </div>

    <table class="table">
        @if ($transactions->count() > 0)
            <thead>
                <tr class="text-center">
                    <th scope="col">#</th>
                    <th scope="col">Bank</th>
                    <th scope="col">Date</th>
                    <th scope="col">Type</th>
                    <th scope="col">Party Name</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Transfer To</th>
                    <th scope="col">Handle</th>
                </tr>
            </thead>
        @endif
        <tbody>
            @forelse ($transactions as $transaction)
                <tr class="text-center">
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $transaction->bank->name }}</td>
                    <td>{{ $transaction->date }}</td>
                    <td>
                        @if ($transaction->type === 'credit')
                            <span class="badge bg-success">Credit</span>
                        @elseif ($transaction->type === 'cash_withdrawal')
                            <span class="badge bg-warning text-dark">Cash Withdrawal</span>
                        @else
                            <span class="badge bg-info text-dark">Bank Transfer</span>
                        @endif
                    </td>
                    <td>{{ $transaction->party_name ?? '-' }}</td>
                    <td>{{ number_format($transaction->amount, 2) }}</td>
                    <td>{{ $transaction->transferToBank?->name ?? '-' }}</td>
                    <td class="d-flex justify-content-center gap-2">
                        <a class="btn btn-primary" href="{{ route('transaction.show', $transaction->id) }}"><i
                                class="fa-solid fa-eye"></i></a>
                        <a class="btn btn-dark" href="{{ route('transaction.edit', $transaction->id) }}"><i
                                class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('transaction.destroy', $transaction->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="if(confirm('Are you sure?')) this.closest('form').submit()"
                                class="btn btn-danger">
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
