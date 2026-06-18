<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bank Summary - {{ $bank->account_title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #333; }
        h1 { font-size: 18px; margin-bottom: 5px; color: #0d6efd; }
        h2 { font-size: 14px; margin-top: 0; color: #666; }
        h3 { font-size: 12px; margin-top: 20px; color: #333; border-bottom: 2px solid #0d6efd; padding-bottom: 4px; }
        .meta td { padding: 3px 15px 3px 0; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 8px; }
        table.data th { background: #0d6efd; color: white; padding: 6px; text-align: left; font-size: 10px; }
        table.data td { padding: 5px 6px; border-bottom: 1px solid #ddd; font-size: 10px; }
        .totals { margin-top: 20px; width: 100%; border: 2px solid #0d6efd; }
        .totals td { padding: 8px 12px; font-weight: bold; }
        .footer { margin-top: 25px; font-size: 10px; color: #999; text-align: center; }
    </style>
</head>
<body>
    <h1>Bank Summary Report</h1>
    <h2>{{ $bank->name }} — {{ $bank->account_title }}</h2>

    <table class="meta">
        <tr>
            <td><strong>Account:</strong> {{ $bank->account_number }}</td>
            <td><strong>Month:</strong> {{ $month->format('F Y') }}</td>
            <td><strong>Generated:</strong> {{ now()->format('d M Y, h:i A') }}</td>
        </tr>
    </table>

    <h3>Incoming Credits ({{ $credits->count() }} entries)</h3>
    <table class="data">
        <thead>
            <tr><th>#</th><th>Date</th><th>Party</th><th style="text-align:right;">Amount</th></tr>
        </thead>
        <tbody>
            @foreach ($credits as $txn)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $txn->date->format('d M Y') }}</td>
                    <td>{{ $txn->party_name ?? '-' }}</td>
                    <td style="text-align:right;">{{ number_format($txn->amount, 2) }}</td>
                </tr>
            @endforeach
            @if ($credits->isEmpty())
                <tr><td colspan="4" style="text-align:center;color:#999;">No entries</td></tr>
            @endif
        </tbody>
    </table>

    <h3>Cash Withdrawals ({{ $cashWithdrawals->count() }} entries)</h3>
    <table class="data">
        <thead>
            <tr><th>#</th><th>Date</th><th style="text-align:right;">Amount</th><th>Notes</th></tr>
        </thead>
        <tbody>
            @foreach ($cashWithdrawals as $txn)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $txn->date->format('d M Y') }}</td>
                    <td style="text-align:right;">{{ number_format($txn->amount, 2) }}</td>
                    <td>{{ $txn->notes ?? '-' }}</td>
                </tr>
            @endforeach
            @if ($cashWithdrawals->isEmpty())
                <tr><td colspan="4" style="text-align:center;color:#999;">No entries</td></tr>
            @endif
        </tbody>
    </table>

    <h3>Bank Transfers ({{ $transfers->count() }} entries)</h3>
    <table class="data">
        <thead>
            <tr><th>#</th><th>Date</th><th>To Bank</th><th style="text-align:right;">Amount</th></tr>
        </thead>
        <tbody>
            @foreach ($transfers as $txn)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $txn->date->format('d M Y') }}</td>
                    <td>{{ $txn->transferToBank?->name ?? '-' }}</td>
                    <td style="text-align:right;">{{ number_format($txn->amount, 2) }}</td>
                </tr>
            @endforeach
            @if ($transfers->isEmpty())
                <tr><td colspan="4" style="text-align:center;color:#999;">No entries</td></tr>
            @endif
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td>Total Credits: Rs. {{ number_format($creditTotal, 2) }} (Limit: Rs. {{ number_format($bank->monthly_limit, 2) }})</td>
            <td>Total Cash Out: Rs. {{ number_format($cashTotal, 2) }}</td>
            <td>Total Transfers: Rs. {{ number_format($transferTotal, 2) }}</td>
        </tr>
    </table>

    <div class="footer">LedgerPro — Bank Summary Report</div>
</body>
</html>
