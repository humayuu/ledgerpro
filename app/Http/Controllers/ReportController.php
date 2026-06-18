<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Transaction;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $banks = Bank::orderBy('name')->get();

        return view('reports.index', compact('banks'));
    }

    public function monthlyCreditPdf(Request $request)
    {
        $validated = $request->validate([
            'bank_id' => ['required', 'exists:banks,id'],
            'month' => ['required', 'date_format:Y-m'],
        ]);

        $bank = Bank::findOrFail($validated['bank_id']);
        $date = Carbon::createFromFormat('Y-m', $validated['month']);

        $transactions = Transaction::with('bank')
            ->where('bank_id', $bank->id)
            ->credit()
            ->forMonth($date->year, $date->month)
            ->orderBy('date')
            ->get();

        $total = $transactions->sum('amount');

        return $this->generatePdf('reports.pdf.monthly-credit', [
            'bank' => $bank,
            'transactions' => $transactions,
            'total' => $total,
            'month' => $date,
            'limit' => $bank->monthly_limit,
            'remaining' => max(0, $bank->monthly_limit - $total),
        ], "incoming-ledger-{$bank->account_title}-{$date->format('Y-m')}.pdf");
    }

    public function outgoingPdf(Request $request)
    {
        $validated = $request->validate([
            'bank_id' => ['required', 'exists:banks,id'],
            'week_start' => ['required', 'date'],
        ]);

        $bank = Bank::findOrFail($validated['bank_id']);
        $weekStart = Carbon::parse($validated['week_start'])->startOfWeek();
        $weekEnd = $weekStart->copy()->endOfWeek();

        $cashWithdrawals = Transaction::with('bank')
            ->where('bank_id', $bank->id)
            ->cashWithdrawal()
            ->forWeek($weekStart, $weekEnd)
            ->orderBy('date')
            ->get();

        $transfers = Transaction::with(['bank', 'transferToBank'])
            ->where('bank_id', $bank->id)
            ->bankTransfer()
            ->forWeek($weekStart, $weekEnd)
            ->orderBy('date')
            ->get();

        $cashTotal = $cashWithdrawals->sum('amount');
        $transferTotal = $transfers->sum('amount');

        return $this->generatePdf('reports.pdf.outgoing', [
            'bank' => $bank,
            'cashWithdrawals' => $cashWithdrawals,
            'transfers' => $transfers,
            'cashTotal' => $cashTotal,
            'transferTotal' => $transferTotal,
            'weekStart' => $weekStart,
            'weekEnd' => $weekEnd,
            'cashLimit' => $bank->weekly_cash_limit,
            'cashRemaining' => max(0, $bank->weekly_cash_limit - $cashTotal),
        ], "outgoing-ledger-{$bank->account_title}-{$weekStart->format('Y-m-d')}.pdf");
    }

    public function bankSummaryPdf(Request $request)
    {
        $validated = $request->validate([
            'bank_id' => ['required', 'exists:banks,id'],
            'month' => ['required', 'date_format:Y-m'],
        ]);

        $bank = Bank::findOrFail($validated['bank_id']);
        $date = Carbon::createFromFormat('Y-m', $validated['month']);

        $credits = Transaction::where('bank_id', $bank->id)
            ->credit()
            ->forMonth($date->year, $date->month)
            ->orderBy('date')
            ->get();

        $cashWithdrawals = Transaction::where('bank_id', $bank->id)
            ->cashWithdrawal()
            ->forMonth($date->year, $date->month)
            ->orderBy('date')
            ->get();

        $transfers = Transaction::with('transferToBank')
            ->where('bank_id', $bank->id)
            ->bankTransfer()
            ->forMonth($date->year, $date->month)
            ->orderBy('date')
            ->get();

        return $this->generatePdf('reports.pdf.bank-summary', [
            'bank' => $bank,
            'month' => $date,
            'credits' => $credits,
            'cashWithdrawals' => $cashWithdrawals,
            'transfers' => $transfers,
            'creditTotal' => $credits->sum('amount'),
            'cashTotal' => $cashWithdrawals->sum('amount'),
            'transferTotal' => $transfers->sum('amount'),
        ], "bank-summary-{$bank->account_title}-{$date->format('Y-m')}.pdf");
    }

    private function generatePdf(string $view, array $data, string $filename)
    {
        $options = new Options;
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', false);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml(view($view, $data)->render());
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"{$filename}\"",
        ]);
    }
}
