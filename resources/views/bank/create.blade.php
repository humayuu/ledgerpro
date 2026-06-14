@extends('layout')
@section('content')
    <h1 class="text-center text-primary fw-bold">Create Bank Details</h1>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <form method="POST" action="{{ route('bank.store') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Bank</label>
            <input type="text" class="form-control" name="name" autofocus>
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="account_title" class="form-label">Account Title</label>
            <input type="text" class="form-control" name="account_title">
            @error('account_title')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="account_number" class="form-label">Account Number</label>
            <input type="text" class="form-control" name="account_number">
            @error('account_number')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="monthly_limit" class="form-label">Monthly Limit</label>
            <input type="number" class="form-control" name="monthly_limit">
            @error('monthly_limit')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="weekly_cash_limit" class="form-label">Monthly Cash Limit</label>
            <input type="number" class="form-control" name="weekly_cash_limit">
            @error('weekly_cash_limit')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="d-flex justify-content-end gap-2">
            <button type="submit" class="btn btn-primary px-3">Create</button>
            <button type="reset" class="btn btn-outline-dark px-3">Reset</button>
            <a href="{{ route('bank.index') }}" class="btn btn-outline-danger px-3">Cancel</a>
        </div>
    </form>
@endsection
