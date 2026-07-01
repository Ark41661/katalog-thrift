<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id', 'parent_product_id',
        'slug', 'name', 'brand',
        'color', 'color_hex', 'style_type', 'product_type',
        'price', 'size', 'size_display', 'size_chart', 'size_unit', 'condition',
        'description', 'story',
        'lookbook_image', 'lookbook_style_tip', 'lookbook_pairing',
        'image', 'image_path',
        'shopee_url', 'tokopedia_url',
        'is_active', 'is_sold', 'is_new_arrival', 'has_variants', 'stock',
        'meta_title', 'meta_description', 'meta_keywords',
        'total_views', 'total_wa_clicks',
    ];

    protected $casts = [
        'is_active'        => 'boolean',
        'is_sold'          => 'boolean',
        'is_new_arrival'   => 'boolean',
        'has_variants'     => 'boolean',
        'lookbook_pairing' => 'array',
        'size_chart'       => 'array',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function reports()
    {
        return $this->hasMany(ProductReport::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function activeVariants()
    {
        return $this->hasMany(ProductVariant::class)->where('is_active', true)->where('is_sold', false);
    }

    public function parentProduct()
    {
        return $this->belongsTo(Product::class, 'parent_product_id');
    }

    public function childProducts()
    {
        return $this->hasMany(Product::class, 'parent_product_id');
    }

    public function questions()
    {
        return $this->hasMany(ProductQuestion::class);
    }

    public function answeredQuestions()
    {
        return $this->hasMany(ProductQuestion::class)->whereNotNull('answer');
    }

    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    public function getReviewCountAttribute(): int
    {
        return $this->reviews()->count();
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image_path) {
            return Storage::url($this->image_path);
        }
        return $this->image ?? '';
    }

    // SEO helpers
    public function getMetaTitleAttribute($value): string
    {
        return $value ?? $this->name . ' - Thrift Secondstore';
    }

    public function getMetaDescriptionAttribute($value): string
    {
        return $value ?? strip_tags(substr($this->description, 0, 160));
    }

    // Increment view count
    public function recordView(): void
    {
        $this->increment('total_views');
    }

    // Scope search
    public function scopeSearch($query, string $term)
    {
        $like = '%' . $term . '%';

        return $query->where(function ($q) use ($term, $like) {
            if (config('database.default') === 'mysql') {
                $q->whereRaw('MATCH(name, brand, description) AGAINST(? IN BOOLEAN MODE)', [$term . '*']);
            } else {
                $q->where('name', 'like', $like)
                  ->orWhere('brand', 'like', $like)
                  ->orWhere('description', 'like', $like);
            }

            // Also match against partner store name
            $q->orWhereHas('partner', fn($pq) => $pq->where('store_name', 'like', $like));
        });
    }
}
