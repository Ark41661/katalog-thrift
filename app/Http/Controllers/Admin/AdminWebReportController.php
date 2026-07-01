<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebReport;
use App\Models\User;
use App\Notifications\GenericNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminWebReportController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->get('status', 'pending');
        $reports = WebReport::with(['user', 'resolver'])
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(30);

        return view('admin.web-reports.index', [
            'reports'      => $reports,
            'activeStatus' => $status,
            'storeName'    => config('catalog.store_name'),
        ]);
    }

    public function show(WebReport $webReport): View
    {
        return view('admin.web-reports.show', [
            'report'    => $webReport->load(['user', 'resolver']),
            'storeName' => config('catalog.store_name'),
        ]);
    }

    public function resolve(Request $request, WebReport $webReport): RedirectResponse
    {
        $data = $request->validate([
            'resolution_note' => ['nullable', 'string', 'max:500'],
        ]);

        $webReport->update([
            'status'          => 'resolved',
            'resolved_by'     => auth()->id(),
            'resolution_note' => $data['resolution_note'] ?? null,
        ]);

        return redirect()->route('admin.web-reports.index', ['status' => 'all'])
            ->with('success', 'Laporan ditandai selesai.');
    }

    public function ignore(Request $request, WebReport $webReport): RedirectResponse
    {
        $data = $request->validate([
            'resolution_note' => ['nullable', 'string', 'max:500'],
        ]);

        $webReport->update([
            'status'          => 'ignored',
            'resolved_by'     => auth()->id(),
            'resolution_note' => $data['resolution_note'] ?? null,
        ]);

        return redirect()->route('admin.web-reports.index', ['status' => 'all'])
            ->with('success', 'Laporan diabaikan.');
    }
}
