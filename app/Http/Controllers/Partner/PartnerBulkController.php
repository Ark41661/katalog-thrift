<?php
namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PartnerBulkController extends Controller
{
    private function partner()
    {
        return auth('partner')->user()->partner;
    }

    public function bulkUpdate(Request $request): RedirectResponse
    {
        $partner = $this->partner();
        $data = $request->validate([
            'product_ids' => ['required', 'array'],
            'product_ids.*' => ['integer', 'exists:products,id'],
            'action'      => ['required', 'in:activate,deactivate,mark_sold,mark_new_arrival'],
        ]);

        $products = Product::whereIn('id', $data['product_ids'])
            ->where('partner_id', $partner->id)
            ->get();

        foreach ($products as $product) {
            match ($data['action']) {
                'activate'        => $product->update(['is_active' => true]),
                'deactivate'      => $product->update(['is_active' => false]),
                'mark_sold'       => $product->update(['is_sold' => true]),
                'mark_new_arrival'=> $product->update(['is_new_arrival' => true]),
            };
        }

        $count = $products->count();

        $partner->recalculateTier();

        return back()->with('success', "{$count} produk berhasil diperbarui.");
    }

    public function bulkDelete(Request $request): RedirectResponse
    {
        $partner = $this->partner();
        $data = $request->validate([
            'product_ids' => ['required', 'array'],
            'product_ids.*' => ['integer', 'exists:products,id'],
        ]);

        $count = Product::whereIn('id', $data['product_ids'])
            ->where('partner_id', $partner->id)
            ->delete();

        $partner->recalculateTier();

        return back()->with('success', "{$count} produk berhasil dihapus.");
    }

    public function export(Request $request)
    {
        $partner = $this->partner();
        $products = $partner->products()->latest()->get();

        $csv = "Nama,Brand,Kategori,Harga,Size,Kondisi,Status\n";
        foreach ($products as $p) {
            $status = $p->is_sold ? 'Sold' : ($p->is_active ? 'Aktif' : 'Nonaktif');
            $csv .= "\"{$p->name}\",\"{$p->brand}\",\"{$p->product_type}\",{$p->price},\"{$p->size}\",\"{$p->condition}\",{$status}\n";
        }

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="produk-' . Str::slug($partner->store_name) . '.csv"',
        ]);
    }
}
