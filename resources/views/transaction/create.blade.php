@extends('layout')
@section('content')
    <h1 class="text-center text-primary fw-bold">Create Transaction</h1>

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

    <form method="POST" action="{{ route('transaction.store') }}">
        @csrf

        <div class="mb-3">
            <label for="bank_id" class="form-label">Bank Account</label>
            <select class="form-select" name="bank_id" id="bank_id" autofocus>
                <option value="">-- Select Bank --</option>
                @foreach ($banks as $bank)
                    <option value="{{ $bank->id }}" {{ old('bank_id', request('bank_id')) == $bank->id ? 'selected' : '' }}>
                        {{ $bank->name }} ({{ $bank->account_title }})
                    </option>
                @endforeach
            </select>
            @error('bank_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" name="date" id="date"
                value="{{ old('date', date('Y-m-d')) }}">
            @error('date')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="type" class="form-label">Transaction Type</label>
            <select class="form-select" name="type" id="type" onchange="toggleFields()">
                <option value="">-- Select Type --</option>
                <option value="credit" {{ old('type') == 'credit' ? 'selected' : '' }}>Credit (Money Received)</option>
                <option value="cash_withdrawal" {{ old('type') == 'cash_withdrawal' ? 'selected' : '' }}>Cash Withdrawal
                </option>
                <option value="bank_transfer" {{ old('type') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
            </select>
            @error('type')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        {{-- Party Name - only for credit --}}
        <div class="mb-3" id="party_name_field" style="display: none;">
            <label for="party_name" class="form-label">Party Name</label>
            <input type="text" class="form-control" name="party_name" id="party_name" value="{{ old('party_name') }}">
            @error('party_name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        {{-- Transfer To Bank - only for bank_transfer --}}
        <div class="mb-3" id="transfer_to_bank_field" style="display: none;">
            <label for="transfer_to_bank_id" class="form-label">Transfer To Bank</label>
            <select class="form-select" name="transfer_to_bank_id" id="transfer_to_bank_id">
                <option value="">-- Select Bank --</option>
                @foreach ($banks as $bank)
                    <option value="{{ $bank->id }}" {{ old('transfer_to_bank_id') == $bank->id ? 'selected' : '' }}>
                        {{ $bank->name }} ({{ $bank->account_title }})
                    </option>
                @endforeach
            </select>
            @error('transfer_to_bank_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" step="0.01" class="form-control" name="amount" id="amount"
                value="{{ old('amount') }}">
            @error('amount')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea class="form-control" name="notes" id="notes" rows="3">{{ old('notes') }}</textarea>
            @error('notes')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="d-flex justify-content-end gap-2">
            <button type="submit" class="btn btn-primary px-3">Create</button>
            <button type="reset" class="btn btn-outline-dark px-3">Reset</button>
            <a href="{{ route('transaction.index') }}" class="btn btn-outline-danger px-3">Cancel</a>
        </div>
    </form>

    <script>
        function toggleFields() {
            const type = document.getElementById('type').value;
            const partyField = document.getElementById('party_name_field');
            const transferField = document.getElementById('transfer_to_bank_field');

            partyField.style.display = type === 'credit' ? 'block' : 'none';
            transferField.style.display = type === 'bank_transfer' ? 'block' : 'none';
        }

        // Page load pe bhi run karo (old input ke liye)
        document.addEventListener('DOMContentLoaded', toggleFields);
    </script>
@endsection
