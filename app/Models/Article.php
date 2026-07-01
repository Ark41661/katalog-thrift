<?php
namespace App\Models;

use App\Support\ArticleCoverHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    protected $fillable = [
        'slug', 'title', 'category', 'cover_image', 'excerpt',
        'content', 'author', 'is_published', 'published_at', 'related_product_ids',
    ];

    protected $casts = [
        'is_published'        => 'boolean',
        'published_at'        => 'datetime',
        'related_product_ids' => 'array',
    ];

    public function relatedProducts()
    {
        if (empty($this->related_product_ids)) return collect();
        return Product::with('partner')->whereIn('id', $this->related_product_ids)->get();
    }

    public function getReadTimeAttribute(): int
    {
        $words = str_word_count(strip_tags($this->content));
        return max(1, (int) ceil($words / 200));
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        return ArticleCoverHelper::resolveUrl($this->cover_image);
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base; $i = 1;
        while (static::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$base}-{$i}"; $i++;
        }
        return $slug;
    }
}
