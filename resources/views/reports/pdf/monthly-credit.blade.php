<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Incoming Ledger - {{ $bank->account_title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 18px; margin-bottom: 5px; color: #198754; }
        h2 { font-size: 14px; margin-top: 0; color: #666; }
        .meta { margin-bottom: 20px; }
        .meta td { padding: 3px 15px 3px 0; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table.data th { background: #198754; color: white; padding: 8px; text-align: left; }
        table.data td { padding: 7px 8px; border-bottom: 1px solid #ddd; }
        table.data tr:nth-child(even) { background: #f8f9fa; }
        .summary { margin-top: 20px; width: 100%; }
        .summary td { padding: 5px 10px; }
        .summary .label { font-weight: bold; }
        .limit-exceeded { color: #dc3545; font-weight: bold; }
        .footer { margin-top: 30px; font-size: 10px; color: #999; text-align: center; }
    </style>
</head>
<body>
    <h1>Incoming Ledger Report (File 1)</h1>
    <h2>{{ $bank->name }} — {{ $bank->account_title }}</h2>

    <table class="meta">
        <tr>
            <td><strong>Account Number:</strong> {{ $bank->account_number }}</td>
            <td><strong>Month:</strong> {{ $month->format('F Y') }}</td>
            <td><strong>Generated:</strong> {{ now()->format('d M Y, h:i A') }}</td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Party Name</th>
                <th style="text-align: right;">Amount (Rs.)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $txn)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $txn->date->format('d M Y') }}</td>
                    <td>{{ $txn->party_name ?? '-' }}</td>
                    <td style="text-align: right;">{{ number_format($txn->amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #999;">No credit entries for this month.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="summary">
        <tr>
            <td class="label">Total Received:</td>
            <td>Rs. {{ number_format($total, 2) }}</td>
            <td class="label">Monthly Limit:</td>
            <td>Rs. {{ number_format($limit, 2) }}</td>
        </tr>
        <tr>
            <td class="label">Remaining:</td>
            <td class="{{ $remaining <= 0 ? 'limit-exceeded' : '' }}">Rs. {{ number_format($remaining, 2) }}</td>
            <td class="label">Usage:</td>
            <td class="{{ $total > $limit ? 'limit-exceeded' : '' }}">
                {{ $limit > 0 ? number_format(($total / $limit) * 100, 1) : 0 }}%
                @if ($total > $limit) — LIMIT EXCEEDED @endif
            </td>
        </tr>
    </table>

    <div class="footer">LedgerPro — Incoming Credit Report</div>
</body>
</html>
