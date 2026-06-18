@extends('layout')
@section('content')
    <h1 class="text-center text-primary fw-bold">Bank Details</h1>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="d-flex justify-content-end">
        <a href="{{ route('bank.create') }}" class="btn btn-primary m-3">Create Account</a>
    </div>
    <table class="table table-hover">
        @if ($banks->count() > 0)
            <thead class="table-dark">
                <tr class="text-center">
                    <th scope="col">#</th>
                    <th scope="col">Bank</th>
                    <th scope="col">Account Title</th>
                    <th scope="col">Monthly Limit</th>
                    <th scope="col">Weekly Cash Limit</th>
                    <th scope="col">Handle</th>
                </tr>
            </thead>
        @endif
        <tbody>
            @forelse ($banks as $bank)
                <tr class="text-center">
                    <th scope="row">{{ $loop->iteration + ($banks->currentPage() - 1) * $banks->perPage() }}</th>
                    <td>{{ $bank->name }}</td>
                    <td>{{ $bank->account_title }}</td>
                    <td>{{ number_format($bank->monthly_limit) }}</td>
                    <td>{{ number_format($bank->weekly_cash_limit) }}</td>
                    <td class="d-flex justify-content-center gap-2">
                        <a class="btn btn-primary btn-sm" href="{{ route('bank.show', $bank) }}"><i class="fa-solid fa-eye"></i></a>
                        <a class="btn btn-dark btn-sm" href="{{ route('bank.edit', $bank) }}"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('bank.destroy', $bank) }}">
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
                    <td colspan="6">
                        <div class="alert alert-info mt-4 mb-0 text-center">No Record Found!</div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $banks->links() }}
@endsection
