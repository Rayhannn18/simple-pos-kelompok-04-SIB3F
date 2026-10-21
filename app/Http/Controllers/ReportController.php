<?php

namespace App\Http\Controllers;

use App\Exports\SalesReportExport;
use App\Jobs\ProcessProductImport;
use App\Models\Category;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\ImportError;
use Illuminate\Support\Str;
use App\Imports\ProductImport;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $request->validate([
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
        ]);

        [$from, $to] = $this->resolveDateRange($request);

        $query = $this->filteredQuery(
            $from,
            $to,
            $request->input('category_id')
        );

        return view('reports.index', [
            'totalPenjualan' => (clone $query)->sum('total'),
            'jumlahTransaksi' => (clone $query)->count(),
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $request->validate([
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
        ]);

        [$from, $to] = $this->resolveDateRange($request);

        $query = $this->filteredQuery(
            $from,
            $to,
            $request->input('category_id')
        );

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');

            fputcsv($out, ['ID', 'Tanggal', 'Kasir', 'Total']);

            foreach ($query->lazyById() as $transaction) {
                fputcsv($out, [
                    $transaction->id,
                    $transaction->created_at,
                    $transaction->user->name,
                    $transaction->total,
                ]);
            }

            fclose($out);
        }, 'laporan-penjualan.csv');
    }

    public function exportExcel(Request $request): BinaryFileResponse
    {
        $request->validate([
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
        ]);

        [$from, $to] = $this->resolveDateRange($request);

        $query = $this->filteredQuery(
            $from,
            $to,
            $request->input('category_id')
        );

        return Excel::download(
            new SalesReportExport($query),
            'laporan-penjualan.xlsx',
        );
    }

    public function exportPdf(Request $request): Response
    {
        $request->validate([
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
        ]);

        [$from, $to] = $this->resolveDateRange($request);

        $query = $this->filteredQuery(
            $from,
            $to,
            $request->input('category_id')
        );

        $pdf = Pdf::loadView('reports.pdf', [
            'totalPenjualan' => (clone $query)->sum('total'),
            'jumlahTransaksi' => (clone $query)->count(),
            'transactions' => $query->latest()->limit(50)->get(),
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
        ]);

        return $pdf->download('laporan-penjualan.pdf');
    }

    public function importForm(): View
    {
        $batch = session('import_batch');

        $importErrors = $batch
            ? ImportError::where('batch', $batch)->orderBy('row_number')->get()
            : collect();

        return view('reports.import', ['importErrors' => $importErrors]);
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'csv' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $batch = (string) Str::uuid();
        $path = $request->file('csv')->store('imports');

        ProcessProductImport::dispatch($path, $batch);

        session()->put('import_batch', $batch);

        return back()->with('success', 'Import sedang diproses di background.');
    }

    public function importExcel(Request $request): RedirectResponse
    {
        $request->validate([
            'csv' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        Excel::import(
            new ProductImport(),
            $request->file('csv')
        );

        return back()->with(
            'success',
            'Import menggunakan Laravel Excel berhasil diproses.'
        );
    }

    private function resolveDateRange(Request $request): array
    {
        $from = $request->date('from')?->startOfDay()
            ?? now()->startOfMonth();

        $to = $request->date('to')?->endOfDay()
            ?? now()->endOfDay();

        return [$from, $to];
    }

    private function filteredQuery(
        $from,
        $to,
        $categoryId = null
    ): Builder {
        $query = Transaction::with('user')
            ->whereBetween('created_at', [$from, $to]);

        if ($categoryId) {
            $query->whereHas('details.product', function ($productQuery) use ($categoryId) {
                $productQuery->where('category_id', $categoryId);
            });
        }

        return $query;
    }
}
