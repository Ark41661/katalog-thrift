<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PartnerProductController extends Controller
{
    private function partner()
    {
        return auth('partner')->user()->partner;
    }

    public function index(): View
    {
        $products = $this->partner()->products()->with('variants')->latest()->get();
        return view('partner.products.index', [
            'partner'      => $this->partner(),
            'products'     => $products,
            'productTypes' => config('catalog.product_types', []),
        ]);
    }

    public function create(): View
    {
        return view('partner.products.create', [
            'partner'          => $this->partner(),
            'productTypes'     => config('catalog.product_types', []),
            'styleTypes'       => ['casual' => 'Casual', 'streetwear' => 'Streetwear', 'sporty' => 'Sporty', 'vintage' => 'Vintage'],
            'sizeChartColumns' => config('catalog.size_chart_columns', []),
            'sizeChartDefaults'=> config('catalog.size_chart_defaults', []),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'brand'        => ['required', 'string', 'max:100'],
            'product_type' => ['required', 'string'],
            'color'        => ['nullable', 'string', 'max:50'],
            'color_hex'    => ['nullable', 'string', 'max:7'],
            'style_type'   => ['nullable', 'string'],
            'price'        => ['required', 'integer', 'min:0'],
            'size'         => ['required', 'string', 'max:50'],
            'condition'    => ['required', 'string', 'max:50'],
            'description'  => ['required', 'string'],
            'story'        => ['nullable', 'string'],
            'image_file'   => ['nullable', 'image', 'max:2048'],
            'image'        => ['nullable', 'url'],
            'shopee_url'   => ['nullable', 'url'],
            'tokopedia_url'=> ['nullable', 'url'],
            'is_active'    => ['nullable', 'boolean'],
            'is_new_arrival'=> ['nullable', 'boolean'],
            'has_size_chart'=> ['nullable', 'boolean'],
            'size_chart'   => ['nullable', 'array'],
            'size_unit'    => ['nullable', 'string', 'max:10'],
            'has_variants' => ['nullable', 'boolean'],
            'variants'     => ['nullable', 'array'],
            'variants.*.size'  => ['nullable', 'string', 'max:20'],
            'variants.*.price' => ['nullable', 'integer', 'min:0'],
            'variants.*.stock' => ['nullable', 'integer', 'min:0'],
            'meta_title'       => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords'    => ['nullable', 'string', 'max:500'],
        ]);

        $partner = $this->partner();
        $sizeChart = $this->parseSizeChart($request);

        // Handle image upload or URL
        $imagePath = null;
        $imageUrl  = $data['image'] ?? null;

        if ($request->hasFile('image_file')) {
            $imagePath = $request->file('image_file')
                ->store("products/{$partner->id}", 'public');
            $imageUrl  = null;
        }

        $product = Product::create([
            'partner_id'    => $partner->id,
            'slug'          => $this->uniqueSlug($data['name']),
            'name'          => $data['name'],
            'brand'         => $data['brand'],
            'product_type'  => $data['product_type'],
            'color'         => $data['color'] ?? null,
            'color_hex'     => $data['color_hex'] ?? null,
            'style_type'    => $data['style_type'] ?? null,
            'price'         => $data['price'],
            'size'          => $data['size'],
            'size_display'  => $data['size'],
            'size_chart'    => $sizeChart,
            'size_unit'     => $data['size_unit'] ?? 'cm',
            'condition'     => $data['condition'],
            'description'   => $data['description'],
            'story'         => $data['story'] ?? null,
            'image'         => $imageUrl,
            'image_path'    => $imagePath,
            'shopee_url'    => $data['shopee_url'] ?? null,
            'tokopedia_url' => $data['tokopedia_url'] ?? null,
            'is_active'     => $request->boolean('is_active', true),
            'is_new_arrival'=> $request->boolean('is_new_arrival', false),
            'is_sold'       => false,
            'stock'         => 1,
            'has_variants'  => $request->boolean('has_variants', false),
            'meta_title'    => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'meta_keywords'    => $data['meta_keywords'] ?? null,
        ]);

        if ($request->boolean('has_variants') && $request->has('variants')) {
            foreach ($data['variants'] ?? [] as $v) {
                if (empty($v['size'])) continue;
                ProductVariant::create([
                    'product_id' => $product->id,
                    'size'       => $v['size'],
                    'price'      => $v['price'] ?? $product->price,
                    'stock'      => $v['stock'] ?? 1,
                ]);
            }
        }

        $partner->recalculateTier();

        return redirect()->route('partner.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product): View
    {
        abort_if($product->partner_id !== $this->partner()->id, 403);

        return view('partner.products.edit', [
            'partner'          => $this->partner(),
            'product'          => $product,
            'productTypes'     => config('catalog.product_types', []),
            'styleTypes'       => ['casual' => 'Casual', 'streetwear' => 'Streetwear', 'sporty' => 'Sporty', 'vintage' => 'Vintage'],
            'sizeChartColumns' => config('catalog.size_chart_columns', []),
            'sizeChartDefaults'=> config('catalog.size_chart_defaults', []),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        abort_if($product->partner_id !== $this->partner()->id, 403);

        $data = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'brand'        => ['required', 'string', 'max:100'],
            'product_type' => ['required', 'string'],
            'color'        => ['nullable', 'string', 'max:50'],
            'color_hex'    => ['nullable', 'string', 'max:7'],
            'style_type'   => ['nullable', 'string'],
            'price'        => ['required', 'integer', 'min:0'],
            'size'         => ['required', 'string', 'max:50'],
            'condition'    => ['required', 'string', 'max:50'],
            'description'  => ['required', 'string'],
            'story'        => ['nullable', 'string'],
            'image_file'   => ['nullable', 'image', 'max:2048'],
            'image'        => ['nullable', 'url'],
            'shopee_url'   => ['nullable', 'url'],
            'tokopedia_url'=> ['nullable', 'url'],
            'is_active'    => ['nullable', 'boolean'],
            'is_sold'      => ['nullable', 'boolean'],
            'is_new_arrival'=> ['nullable', 'boolean'],
            'has_size_chart'=> ['nullable', 'boolean'],
            'size_chart'   => ['nullable', 'array'],
            'size_unit'    => ['nullable', 'string', 'max:10'],
            'has_variants' => ['nullable', 'boolean'],
            'variants'     => ['nullable', 'array'],
            'variants.*.size'  => ['nullable', 'string', 'max:20'],
            'variants.*.price' => ['nullable', 'integer', 'min:0'],
            'variants.*.stock' => ['nullable', 'integer', 'min:0'],
            'meta_title'       => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords'    => ['nullable', 'string', 'max:500'],
        ]);

        $sizeChart = $this->parseSizeChart($request);
        $imagePath = $product->image_path;
        $imageUrl  = $data['image'] ?? $product->image;

        if ($request->hasFile('image_file')) {
            if ($imagePath) Storage::disk('public')->delete($imagePath);
            $imagePath = $request->file('image_file')
                ->store("products/{$product->partner_id}", 'public');
            $imageUrl  = null;
        }

        if ($data['name'] !== $product->name) {
            $data['slug'] = $this->uniqueSlug($data['name'], $product->id);
        }

        $product->update([
            'name'          => $data['name'],
            'slug'          => $data['slug'] ?? $product->slug,
            'brand'         => $data['brand'],
            'product_type'  => $data['product_type'],
            'color'         => $data['color'] ?? null,
            'color_hex'     => $data['color_hex'] ?? null,
            'style_type'    => $data['style_type'] ?? null,
            'price'         => $data['price'],
            'size'          => $data['size'],
            'size_display'  => $data['size'],
            'size_chart'    => $sizeChart,
            'size_unit'     => $data['size_unit'] ?? $product->size_unit ?? 'cm',
            'condition'     => $data['condition'],
            'description'   => $data['description'],
            'story'         => $data['story'] ?? null,
            'image'         => $imageUrl,
            'image_path'    => $imagePath,
            'shopee_url'    => $data['shopee_url'] ?? null,
            'tokopedia_url' => $data['tokopedia_url'] ?? null,
            'is_active'     => $request->boolean('is_active', false),
            'is_sold'       => $request->boolean('is_sold', false),
            'is_new_arrival'=> $request->boolean('is_new_arrival', false),
            'stock'         => $data['stock'] ?? $product->stock ?? 1,
            'has_variants'  => $request->boolean('has_variants', false),
            'meta_title'    => $data['meta_title'] ?? $product->meta_title,
            'meta_description' => $data['meta_description'] ?? $product->meta_description,
            'meta_keywords'    => $data['meta_keywords'] ?? $product->meta_keywords,
        ]);

        if ($request->boolean('has_variants') && $request->has('variants')) {
            $product->variants()->delete();
            foreach ($data['variants'] ?? [] as $v) {
                if (empty($v['size'])) continue;
                ProductVariant::create([
                    'product_id' => $product->id,
                    'size'       => $v['size'],
                    'price'      => $v['price'] ?? $product->price,
                    'stock'      => $v['stock'] ?? 1,
                ]);
            }
        }

        $product->partner->recalculateTier();

        return redirect()->route('partner.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        abort_if($product->partner_id !== $this->partner()->id, 403);

        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        $this->partner()->recalculateTier();

        return redirect()->route('partner.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    private function parseSizeChart(Request $request): ?array
    {
        if (!$request->boolean('has_size_chart')) {
            return null;
        }

        $rows = $request->input('size_chart', []);
        $parsed = [];

        foreach ($rows as $row) {
            if (empty($row['size'])) {
                continue;
            }
            $parsed[] = array_filter($row, fn ($v) => $v !== null && $v !== '');
        }

        return count($parsed) > 0 ? array_values($parsed) : null;
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i    = 1;
        while (Product::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }
        return $slug;
    }

    // ─── VARIANTS ──────────────────────────────────────────────────────────
    public function saveVariants(Request $request, Product $product): RedirectResponse
    {
        abort_if($product->partner_id !== $this->partner()->id, 403);

        $data = $request->validate([
            'variants'           => ['required', 'array', 'min:1'],
            'variants.*.size'    => ['required', 'string', 'max:20'],
            'variants.*.price'   => ['nullable', 'integer', 'min:0'],
            'variants.*.condition' => ['nullable', 'string', 'max:50'],
            'variants.*.stock'   => ['nullable', 'integer', 'min:0'],
        ]);

        // Delete existing variants
        $product->variants()->delete();

        foreach ($data['variants'] as $v) {
            ProductVariant::create([
                'product_id' => $product->id,
                'size'       => $v['size'],
                'price'      => $v['price'] ?? $product->price,
                'condition'  => $v['condition'] ?? $product->condition,
                'stock'      => $v['stock'] ?? 1,
            ]);
        }

        $product->update(['has_variants' => true]);

        $product->partner->recalculateTier();

        return back()->with('success', 'Varian produk berhasil disimpan.');
    }

    public function destroyVariant(Product $product, ProductVariant $variant): RedirectResponse
    {
        abort_if($product->partner_id !== $this->partner()->id, 403);
        abort_if($variant->product_id !== $product->id, 404);

        $variant->delete();

        if ($product->variants()->count() === 0) {
            $product->update(['has_variants' => false]);
        }

        $product->partner->recalculateTier();

        return back()->with('success', 'Varian dihapus.');
    }
}
