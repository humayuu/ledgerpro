@extends('layout')
@section('content')
    <h1 class="text-center text-primary fw-bold mb-4">Reports</h1>
    <p class="text-muted text-center mb-4">Generate PDF reports for incoming credits, outgoing cash/transfer, and full bank summary.</p>

    <div class="row g-4">
        {{-- Report 1: Incoming Ledger (File 1) --}}
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-success text-white">
                    <i class="fa-solid fa-arrow-down me-2"></i>Incoming Ledger (File 1)
                </div>
                <div class="card-body">
                    <p class="text-muted small">Monthly credit report — Date, Party, Amount with 7 lac limit summary.</p>
                    <form action="{{ route('reports.monthly-credit') }}" method="GET" target="_blank">
                        <div class="mb-3">
                            <label class="form-label">Bank Account</label>
                            <select name="bank_id" class="form-select" required>
                                <option value="">-- Select Bank --</option>
                                @foreach ($banks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }} ({{ $bank->account_title }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Month</label>
                            <input type="month" name="month" class="form-control" value="{{ now()->format('Y-m') }}" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fa-solid fa-file-pdf me-1"></i> Download PDF
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Report 2: Outgoing Ledger (File 2) --}}
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <i class="fa-solid fa-arrow-up me-2"></i>Outgoing Ledger (File 2)
                </div>
                <div class="card-body">
                    <p class="text-muted small">Weekly cash withdrawals and bank transfers with 9.5 lac cash limit.</p>
                    <form action="{{ route('reports.outgoing') }}" method="GET" target="_blank">
                        <div class="mb-3">
                            <label class="form-label">Bank Account</label>
                            <select name="bank_id" class="form-select" required>
                                <option value="">-- Select Bank --</option>
                                @foreach ($banks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }} ({{ $bank->account_title }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Week (any date in week)</label>
                            <input type="date" name="week_start" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                        </div>
                        <button type="submit" class="btn btn-warning w-100">
                            <i class="fa-solid fa-file-pdf me-1"></i> Download PDF
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Report 3: Full Bank Summary --}}
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <i class="fa-solid fa-file-lines me-2"></i>Bank Summary
                </div>
                <div class="card-body">
                    <p class="text-muted small">Complete monthly summary — credits, cash withdrawals, and transfers.</p>
                    <form action="{{ route('reports.bank-summary') }}" method="GET" target="_blank">
                        <div class="mb-3">
                            <label class="form-label">Bank Account</label>
                            <select name="bank_id" class="form-select" required>
                                <option value="">-- Select Bank --</option>
                                @foreach ($banks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }} ({{ $bank->account_title }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Month</label>
                            <input type="month" name="month" class="form-control" value="{{ now()->format('Y-m') }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fa-solid fa-file-pdf me-1"></i> Download PDF
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
