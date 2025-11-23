<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Display reports dashboard.
     */
    public function index()
    {
        return view('user.reports.index');
    }

    /**
     * Show revenue report.
     */
    public function revenue(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::now()->endOfMonth()->format('Y-m-d');

        $report = $this->reportService->getRevenueReport(
            auth()->id(),
            $startDate,
            $endDate
        );

        return view('user.reports.revenue', compact('report', 'startDate', 'endDate'));
    }

    /**
     * Show expense report.
     */
    public function expenses(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::now()->endOfMonth()->format('Y-m-d');

        $report = $this->reportService->getExpenseReport(
            auth()->id(),
            $startDate,
            $endDate
        );

        return view('user.reports.expenses', compact('report', 'startDate', 'endDate'));
    }

    /**
     * Show profit/loss report.
     */
    public function profitLoss(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::now()->endOfMonth()->format('Y-m-d');

        $report = $this->reportService->getProfitLossReport(
            auth()->id(),
            $startDate,
            $endDate
        );

        // Get chart data for last 6 months
        $chartData = $this->reportService->getRevenueVsExpensesChartData(auth()->id(), 6);

        return view('user.reports.profit-loss', compact('report', 'startDate', 'endDate', 'chartData'));
    }

    /**
     * Show client-wise report.
     */
    public function clients()
    {
        $report = $this->reportService->getClientReport(auth()->id());

        // Get chart data
        $chartData = $this->reportService->getRevenueByClientChartData(auth()->id(), 10);

        return view('user.reports.clients', compact('report', 'chartData'));
    }

    /**
     * Export revenue report to PDF.
     */
    public function exportRevenuePdf(Request $request, PdfService $pdfService)
    {
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::now()->endOfMonth()->format('Y-m-d');

        $report = $this->reportService->getRevenueReport(
            auth()->id(),
            $startDate,
            $endDate
        );

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.revenue-report', [
            'report' => $report,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'user' => auth()->user(),
        ]);

        return $pdf->download('revenue-report-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export expense report to PDF.
     */
    public function exportExpensePdf(Request $request, PdfService $pdfService)
    {
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::now()->endOfMonth()->format('Y-m-d');

        $report = $this->reportService->getExpenseReport(
            auth()->id(),
            $startDate,
            $endDate
        );

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.expense-report', [
            'report' => $report,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'user' => auth()->user(),
        ]);

        return $pdf->download('expense-report-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export profit/loss report to PDF.
     */
    public function exportProfitLossPdf(Request $request, PdfService $pdfService)
    {
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::now()->endOfMonth()->format('Y-m-d');

        $report = $this->reportService->getProfitLossReport(
            auth()->id(),
            $startDate,
            $endDate
        );

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.profit-loss-report', [
            'report' => $report,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'user' => auth()->user(),
        ]);

        return $pdf->download('profit-loss-report-' . date('Y-m-d') . '.pdf');
    }
}
