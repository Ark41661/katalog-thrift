<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with('partner')
            ->latest()
            ->paginate(30);

        return view('admin.products.index', [
            'storeName'    => config('catalog.store_name'),
            'products'     => $products,
            'productTypes' => config('catalog.product_types', []),
        ]);
    }

    public function suspend(Product $product): RedirectResponse
    {
        $product->update(['is_active' => ! $product->is_active]);
        $label = $product->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Produk \"{$product->name}\" {$label}.");
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
        return back()->with('success', 'Produk berhasil dihapus.');
    }
}
