<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Outfit extends Model
{
    protected $fillable = [
        'title', 'description', 'style_type',
        'created_by_type', 'created_by_id',
        'partner_id', 'cover_image', 'cover_video',
        'is_active', 'share_token',
    ];

    protected $casts = ['is_active' => 'boolean'];

    protected static function booted(): void
    {
        static::creating(function (Outfit $outfit) {
            if (empty($outfit->share_token)) {
                $outfit->share_token = Str::random(12);
            }
        });
    }

    public function items()
    {
        return $this->hasMany(OutfitItem::class)->orderBy('sort_order');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'outfit_items')
            ->withPivot('sort_order', 'note')
            ->orderByPivot('sort_order');
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function saves()
    {
        return $this->hasMany(OutfitSave::class);
    }

    public function getTotalPriceAttribute(): int
    {
        return $this->products->sum('price');
    }

    public function getShareUrlAttribute(): string
    {
        return route('outfit.share', $this->share_token);
    }
}
