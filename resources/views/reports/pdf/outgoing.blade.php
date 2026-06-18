<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Outgoing Ledger - {{ $bank->account_title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 18px; margin-bottom: 5px; color: #fd7e14; }
        h2 { font-size: 14px; margin-top: 0; color: #666; }
        h3 { font-size: 13px; margin-top: 25px; color: #333; border-bottom: 2px solid #fd7e14; padding-bottom: 5px; }
        .meta { margin-bottom: 20px; }
        .meta td { padding: 3px 15px 3px 0; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data th { background: #fd7e14; color: white; padding: 8px; text-align: left; }
        table.data td { padding: 7px 8px; border-bottom: 1px solid #ddd; }
        table.data tr:nth-child(even) { background: #f8f9fa; }
        .summary { margin-top: 15px; }
        .summary td { padding: 4px 10px; }
        .summary .label { font-weight: bold; }
        .limit-exceeded { color: #dc3545; font-weight: bold; }
        .footer { margin-top: 30px; font-size: 10px; color: #999; text-align: center; }
    </style>
</head>
<body>
    <h1>Outgoing Ledger Report (File 2)</h1>
    <h2>{{ $bank->name }} — {{ $bank->account_title }}</h2>

    <table class="meta">
        <tr>
            <td><strong>Account Number:</strong> {{ $bank->account_number }}</td>
            <td><strong>Week:</strong> {{ $weekStart->format('d M Y') }} — {{ $weekEnd->format('d M Y') }}</td>
            <td><strong>Generated:</strong> {{ now()->format('d M Y, h:i A') }}</td>
        </tr>
    </table>

    <h3>Cash Withdrawals</h3>
    <table class="data">
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th style="text-align: right;">Amount (Rs.)</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($cashWithdrawals as $txn)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $txn->date->format('d M Y') }}</td>
                    <td style="text-align: right;">{{ number_format($txn->amount, 2) }}</td>
                    <td>{{ $txn->notes ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #999;">No cash withdrawals this week.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="summary">
        <tr>
            <td class="label">Total Cash Withdrawn:</td>
            <td>Rs. {{ number_format($cashTotal, 2) }}</td>
            <td class="label">Weekly Cash Limit:</td>
            <td>Rs. {{ number_format($cashLimit, 2) }}</td>
        </tr>
        <tr>
            <td class="label">Remaining:</td>
            <td class="{{ $cashRemaining <= 0 ? 'limit-exceeded' : '' }}">Rs. {{ number_format($cashRemaining, 2) }}</td>
            <td class="label">Usage:</td>
            <td class="{{ $cashTotal > $cashLimit ? 'limit-exceeded' : '' }}">
                {{ $cashLimit > 0 ? number_format(($cashTotal / $cashLimit) * 100, 1) : 0 }}%
                @if ($cashTotal > $cashLimit) — LIMIT EXCEEDED @endif
            </td>
        </tr>
    </table>

    <h3>Bank Transfers</h3>
    <table class="data">
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Transfer To</th>
                <th style="text-align: right;">Amount (Rs.)</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transfers as $txn)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $txn->date->format('d M Y') }}</td>
                    <td>{{ $txn->transferToBank?->name ?? '-' }} ({{ $txn->transferToBank?->account_title ?? '' }})</td>
                    <td style="text-align: right;">{{ number_format($txn->amount, 2) }}</td>
                    <td>{{ $txn->notes ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #999;">No bank transfers this week.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="summary">
        <tr>
            <td class="label">Total Transferred:</td>
            <td>Rs. {{ number_format($transferTotal, 2) }}</td>
        </tr>
    </table>

    <div class="footer">LedgerPro — Outgoing Ledger Report</div>
</body>
</html>
