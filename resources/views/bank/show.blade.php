@extends('layout')
@section('content')
    <div class="container mt-2">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fa-solid fa-building-columns me-2"></i>{{ $bank->name }} — {{ $bank->account_title }}</h4>
                <a href="{{ route('bank.edit', $bank) }}" class="btn btn-sm btn-light"><i class="fa-solid fa-pen me-1"></i>Edit</a>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Account Number:</strong> {{ $bank->account_number }}
                    </div>
                    <div class="col-md-4">
                        <strong>Monthly Limit:</strong> Rs. {{ number_format($bank->monthly_limit, 2) }}
                    </div>
                    <div class="col-md-4">
                        <strong>Weekly Cash Limit:</strong> Rs. {{ number_format($bank->weekly_cash_limit, 2) }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Monthly Credit ({{ now()->format('M Y') }})</span>
                            <span>Rs. {{ number_format($bank->currentMonthCredit(), 2) }} / Rs. {{ number_format($bank->monthly_limit, 2) }}</span>
                        </div>
                        @php $monthPct = $bank->monthlyCreditUsagePercent(); @endphp
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar {{ $monthPct >= 100 ? 'bg-danger' : 'bg-success' }}" style="width: {{ $monthPct }}%">
                                {{ number_format($monthPct, 1) }}%
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Weekly Cash (This Week)</span>
                            <span>Rs. {{ number_format($bank->currentWeekCashWithdrawal(), 2) }} / Rs. {{ number_format($bank->weekly_cash_limit, 2) }}</span>
                        </div>
                        @php $weekPct = $bank->weeklyCashUsagePercent(); @endphp
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar {{ $weekPct >= 100 ? 'bg-danger' : 'bg-warning' }}" style="width: {{ $weekPct }}%">
                                {{ number_format($weekPct, 1) }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0"><i class="fa-solid fa-list me-2"></i>Transactions</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('transaction.create') }}?bank_id={{ $bank->id }}" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-plus me-1"></i>Add Transaction
                </a>
                <a href="{{ route('reports.index') }}" class="btn btn-outline-danger btn-sm">
                    <i class="fa-solid fa-file-pdf me-1"></i>PDF Reports
                </a>
            </div>
        </div>

        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr class="text-center">
                    <th>#</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Party / Transfer To</th>
                    <th>Amount</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bank->transactions->sortByDesc('date') as $transaction)
                    <tr class="text-center">
                        <td>{{ $loop->iteration }}</td>
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
                        <td>
                            @if ($transaction->type === 'credit')
                                {{ $transaction->party_name ?? '-' }}
                            @elseif ($transaction->type === 'bank_transfer')
                                {{ $transaction->transferToBank?->name ?? '-' }}
                            @else
                                -
                            @endif
                        </td>
                        <td>Rs. {{ number_format($transaction->amount, 2) }}</td>
                        <td>{{ Str::limit($transaction->notes, 30) ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No transactions yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <a href="{{ route('bank.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Banks
        </a>
    </div>
@endsection
