@extends('layout')
@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fa-solid fa-building-columns me-2"></i>Bank Details</h4>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fa-solid fa-landmark me-2 text-primary"></i>Bank Name</span>
                        <span>{{ $bank->name }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fa-solid fa-user me-2 text-primary"></i>Account Title</span>
                        <span>{{ $bank->account_title }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fa-solid fa-hashtag me-2 text-primary"></i>Account Number</span>
                        <span>{{ $bank->account_number ?? 'N/A' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fa-solid fa-calendar-days me-2 text-success"></i>Monthly
                            Limit</span>
                        <span class="badge bg-success fs-6">
                            Rs. {{ number_format($bank->monthly_limit, 2) }}
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fa-solid fa-money-bill-wave me-2 text-warning"></i>Weekly Cash
                            Limit</span>
                        <span class="badge bg-warning text-dark fs-6">
                            Rs. {{ number_format($bank->weekly_cash_limit, 2) }}
                        </span>
                    </li>
                </ul>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('bank.index') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back
                </a>
                <div>
                    <a href="{{ route('bank.edit', $bank->id) }}" class="btn btn-warning">
                        <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                    </a>
                    <form action="{{ route('bank.destroy', $bank->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                            <i class="fa-solid fa-trash me-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
