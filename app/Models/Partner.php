<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Partner extends Model
{
    protected $fillable = [
        'user_id',
        'store_name', 'store_slug',
        'description', 'logo', 'location',
        'whatsapp', 'shopee_url', 'tokopedia_url',
        'instagram_url', 'tiktok_url',
        'status', 'rejection_reason',
        'is_verified', 'approved_at',
        'tier', 'total_views', 'total_wa_clicks',
        'total_wishlist_count', 'follower_count', 'analytics_data',
    ];

    protected $casts = [
        'is_verified'    => 'boolean',
        'approved_at'    => 'datetime',
        'analytics_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function activeProducts()
    {
        return $this->hasMany(Product::class)
            ->where('is_active', true)
            ->where('is_sold', false);
    }

    public function followers()
    {
        return $this->hasMany(Follower::class);
    }

    public function getLogoUrlAttribute(): string
    {
        if (!$this->logo) {
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->store_name) . '&background=111827&color=fff&size=128';
        }
        if (str_starts_with($this->logo, 'http')) {
            return $this->logo;
        }
        return Storage::url($this->logo);
    }

    public function getAverageRatingAttribute(): float
    {
        $avg = Review::whereIn('product_id', $this->products()->pluck('id'))->avg('rating');
        return round($avg ?? 0, 1);
    }

    public function getReviewCountAttribute(): int
    {
        return Review::whereIn('product_id', $this->products()->pluck('id'))->count();
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    // Tier helpers
    public function getTierBadgeAttribute(): string
    {
        return match($this->tier) {
            'platinum' => '💎',
            'gold'     => '🥇',
            'silver'   => '🥈',
            default    => '🥉',
        };
    }

    public function getTierNameAttribute(): string
    {
        return match($this->tier) {
            'platinum' => 'Platinum',
            'gold'     => 'Gold',
            'silver'   => 'Silver',
            default    => 'Bronze',
        };
    }

    // Auto-calculate tier based on performance
    public function recalculateTier(): void
    {
        $score = 0;
        $score += $this->activeProducts()->count() * 2;
        $score += $this->average_rating * 5;
        $score += $this->follower_count * 1;
        $score += $this->total_views > 1000 ? 10 : 0;
        $score += $this->review_count * 2;

        $tier = match(true) {
            $score >= 100 => 'platinum',
            $score >= 60  => 'gold',
            $score >= 30  => 'silver',
            default       => 'bronze',
        };

        $this->update(['tier' => $tier]);
    }
}
