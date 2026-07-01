<?php

namespace App\Http\Controllers;

use App\Models\ProductReport;
use App\Models\ReportStatusHistory;
use App\Notifications\GenericNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminReportController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->get('status', 'pending');
        $reports = ProductReport::with(['user', 'product.partner'])
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(30);

        return view('admin.reports.index', [
            'reports'      => $reports,
            'activeStatus' => $status,
            'storeName'    => config('catalog.store_name'),
        ]);
    }

    public function show(ProductReport $report): View
    {
        return view('admin.reports.show', [
            'report'    => $report->load(['user', 'product.partner', 'history.admin']),
            'storeName' => config('catalog.store_name'),
        ]);
    }

    public function resolve(Request $request, ProductReport $report): RedirectResponse
    {
        $data = $request->validate([
            'resolution_note' => ['nullable', 'string', 'max:500'],
        ]);

        $oldStatus = $report->status;

        $report->update([
            'status'          => 'resolved',
            'resolved_by'     => auth()->id(),
            'resolution_note' => $data['resolution_note'] ?? null,
        ]);

        $this->logHistory($report, 'resolved', $data['resolution_note'] ?? null);
        $this->notifyReportUpdate($report, 'Laporan kamu untuk ' . $report->product->name . ' telah diselesaikan.');

        return redirect()->route('admin.reports.index', ['status' => 'all'])
            ->with('success', 'Laporan ditandai selesai.');
    }

    public function ignore(Request $request, ProductReport $report): RedirectResponse
    {
        $data = $request->validate([
            'resolution_note' => ['nullable', 'string', 'max:500'],
        ]);

        $report->update([
            'status'          => 'ignored',
            'resolved_by'     => auth()->id(),
            'resolution_note' => $data['resolution_note'] ?? null,
        ]);

        $this->logHistory($report, 'ignored', $data['resolution_note'] ?? null);
        $this->notifyReportUpdate($report, 'Laporan kamu untuk ' . $report->product->name . ' diabaikan.');

        return redirect()->route('admin.reports.index', ['status' => 'all'])
            ->with('success', 'Laporan diabaikan.');
    }

    private function logHistory(ProductReport $report, string $status, ?string $note): void
    {
        ReportStatusHistory::create([
            'product_report_id' => $report->id,
            'status'            => $status,
            'action_by'         => auth()->id(),
            'note'              => $note,
        ]);
    }

    private function notifyReportUpdate(ProductReport $report, string $message): void
    {
        if ($report->user) {
            $report->user->notify(new GenericNotification(
                $message,
                '✅',
                route('catalog.show', $report->product->slug)
            ));
        }

        if ($report->product->partner?->user) {
            $report->product->partner->user->notify(new GenericNotification(
                'Status laporan untuk ' . $report->product->name . ' telah diperbarui.',
                '🚨',
                route('partner.dashboard')
            ));
        }
    }
}
