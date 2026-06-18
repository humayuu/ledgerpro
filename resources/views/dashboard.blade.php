@extends('layout')
@section('content')
    <h1 class="text-center text-primary fw-bold mb-4">Dashboard</h1>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-primary h-100">
                <div class="card-body text-center">
                    <i class="fa-solid fa-building-columns fa-2x text-primary mb-2"></i>
                    <h5 class="card-title">Total Banks</h5>
                    <p class="display-6 fw-bold mb-0">{{ $stats['total_banks'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info h-100">
                <div class="card-body text-center">
                    <i class="fa-solid fa-money-bill-transfer fa-2x text-info mb-2"></i>
                    <h5 class="card-title">Transactions</h5>
                    <p class="display-6 fw-bold mb-0">{{ $stats['total_transactions'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success h-100">
                <div class="card-body text-center">
                    <i class="fa-solid fa-arrow-down fa-2x text-success mb-2"></i>
                    <h5 class="card-title">This Month Credits</h5>
                    <p class="fs-4 fw-bold mb-0">Rs. {{ number_format($stats['month_credits'], 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning h-100">
                <div class="card-body text-center">
                    <i class="fa-solid fa-money-bill-wave fa-2x text-warning mb-2"></i>
                    <h5 class="card-title">This Week Cash</h5>
                    <p class="fs-4 fw-bold mb-0">Rs. {{ number_format($stats['week_cash'], 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <h4 class="fw-bold mb-3"><i class="fa-solid fa-chart-bar me-2"></i>Bank Limits Overview</h4>

    @forelse ($banks as $bank)
        <div class="card mb-3 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $bank->name }}</strong> — {{ $bank->account_title }}
                </div>
                <a href="{{ route('bank.show', $bank) }}" class="btn btn-sm btn-outline-primary">View Details</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Monthly Credit ({{ now()->format('M Y') }})</span>
                            <span>
                                Rs. {{ number_format($bank->currentMonthCredit(), 2) }}
                                / Rs. {{ number_format($bank->monthly_limit, 2) }}
                            </span>
                        </div>
                        @php $monthPct = $bank->monthlyCreditUsagePercent(); @endphp
                        <div class="progress" style="height: 22px;">
                            <div class="progress-bar {{ $monthPct >= 100 ? 'bg-danger' : ($monthPct >= 80 ? 'bg-warning' : 'bg-success') }}"
                                style="width: {{ $monthPct }}%">
                                {{ number_format($monthPct, 1) }}%
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Weekly Cash (This Week)</span>
                            <span>
                                Rs. {{ number_format($bank->currentWeekCashWithdrawal(), 2) }}
                                / Rs. {{ number_format($bank->weekly_cash_limit, 2) }}
                            </span>
                        </div>
                        @php $weekPct = $bank->weeklyCashUsagePercent(); @endphp
                        <div class="progress" style="height: 22px;">
                            <div class="progress-bar {{ $weekPct >= 100 ? 'bg-danger' : ($weekPct >= 80 ? 'bg-warning' : 'bg-info') }}"
                                style="width: {{ $weekPct }}%">
                                {{ number_format($weekPct, 1) }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info">
            No banks added yet. <a href="{{ route('bank.create') }}">Create your first bank account</a>.
        </div>
    @endforelse
@endsection
